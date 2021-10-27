<?php

namespace frontend\module\api\v1\controllers;

use Cassandra\Exception\TimeoutException;
use common\models\Car;
use common\models\Garage;
use frontend\module\api\Helper;
use frontend\module\api\RawData;
use frontend\module\api\v1\services\StoreCarService;
use frontend\module\api\v1\services\UserService;
use yii\httpclient\Client;
use Yii;
use yii\log\LogRuntimeException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;


class CarsController extends Controller
{
    public function actionIndex()
    {
        $userService = new UserService(UserService::getSelf());
        return Helper::getResponse($userService->car->all());
    }

    public function actionGarage($id)
    {
        if (!$garage = Garage::findOne($id)) {
            return Helper::getResponse(null, 'Несущесвующий гараж');
        }
        $userService = new UserService(UserService::getSelf());
        return Helper::getResponse($userService->car->allFromGarage($garage));
    }

    /**
     * @throws LogRuntimeException
     */
    public function actionGetTransaction($id)
    {
        if (!Yii::$app->request->isPost)
            return Helper::getResponse(null, 'Only POST request can buy', 400);
        if (!$car = Car::findOne($id))
            return Helper::getResponse(null, 'Несуществующая машина', 400);
        if (!$walletAddress = Yii::$app->request->post('address'))
            return Helper::getResponse(null, 'Not exist some params');

        $client = new Client();
        $responseTransaction = $client->createRequest()
            ->setMethod('POST')
            ->setUrl(Yii::$app->params['apiTronGrid'] . '/wallet/createtransaction')
            ->setContent(json_encode([
                'to_address' => Yii::$app->params['adminWalletAddress'],
                'owner_address' => $walletAddress,
                'amount' => $car->price * 1000000,
                'visible' => true
            ]))
            ->setFormat(Response::FORMAT_JSON)
            ->setHeaders(['Content-Type' => 'application/json'])
            ->send();
        if (!$responseTransaction->isOk) {
            throw new LogRuntimeException();
        }

        $responseBroadcastTransaction = $client->createRequest()
            ->setMethod('POST')
            ->setUrl(Yii::$app->params['apiTronGrid'] . '/wallet/createtransaction')
            ->setContent('{"visible":true,"txID":"64d30b19217c2a91d39db26695cfc12c5653e148b08201a0088b9018e9f02ad2","raw_data":{"contract":[{"parameter":{"value":{"amount":235000000,"to_address":"TM5JhqnbrFobs9ujSonwz5heTqXBU3Xkf1","owner_address":"TXyKyvChDHZrCisWgb1tEGeQ7i4dT1coMt"},"type_url":"type.googleapis.com/protocol.TransferContract"},"type":"TransferContract"}],"ref_block_bytes":"ef13","ref_block_hash":"d75280424b0b621f","expiration":1635326916000,"timestamp":1635326857116},"raw_data_hex":"0a02ef132208d75280424b0b621f40a0ebd188cc2f5a68080112640a2d747970652e676f6f676c65617069732e636f6d2f70726f746f636f6c2e5472616e73666572436f6e747261637412330a1541f1589818cc297d0cb6effd3f3e0b685273ae7a4512154179d0a3e099d3aadbf4aef5a01440129b4b71538118c0a18770709c9fce88cc2f","signature":["6fffd8eae5fddb0eb3bd362725f13d88c22018e8729fd6bf926fcf8885f35379256fec681f01ecb2fafd8844305d62c512edd31c22d0ab42fca7d6c51c04d50601"]}')
            ->setFormat(Response::FORMAT_JSON)
            ->setHeaders(['Content-Type' => 'application/json'])
            ->send();
        if (!$responseBroadcastTransaction->isOk) {
            throw new LogRuntimeException();
        }
        return $responseBroadcastTransaction->data;


        return $response->data;
// use key 'http' even if you send the request to https://...
//        $options = [
//            'http' => [
//                'header' => "Content-type: application/json",
//                'method' => 'POST',
//                'content' => ($data)
//            ]
//        ];
//        $context = stream_context_create($options);
//        $result = file_get_contents($url, false, $context);
//        if ($result === FALSE) {
//            throw new LogRuntimeException();
//        }
//
//        return Helper::getResponse(json_decode($result));
    }


    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @throws BadRequestHttpException
     */
    public function actionBuy($id)
    {
        if (!$signedTransaction = Yii::$app->request->post('signed_transaction')) {
            throw new BadRequestHttpException('Not exist POST param \'signed_transaction\'');
        }
        if (!$car = Car::findOne($id))
            return Helper::getResponse(null, 'Несуществующая машина', 400);

        $rawData = (RawData::createFromSignedTransaction(json_decode($signedTransaction, true)));
        if (!($rawData->HasRightOwnerAddress() && $rawData->amount/1000000 >= $car->price)){
            throw new BadRequestHttpException('Few money or incorrect to_address');
        }


        //Транзакция выполнена
        $client = new Client();
        $response = $client->createRequest()
            ->setUrl(Yii::$app->params['apiTronGrid'] . "/wallet/broadcasttransaction")
            ->setMethod('POST')
            ->setContent($signedTransaction)
            ->setFormat(Response::FORMAT_JSON)
            ->setHeaders(['Content-Type' => 'application/json'])
            ->send();

        if (!$response->isOk) {
            throw new BadRequestHttpException('Неверный адрес кошелька в параметре \'address\'');
        }

        if (isset($response->data['code']) && $response->data['code'] === 'TRANSACTION_EXPIRATION_ERROR')
            throw new BadRequestHttpException('TRANSACTION_EXPIRATION_ERROR');


        if (!(isset($response->data['result'])&&$response->data['result'] === true))
            throw new BadRequestHttpException();


        $storeCarService = new StoreCarService(new UserService(UserService::getSelf()), $car);
        if (!$storeCarService->buyCar())
            return Helper::getResponse(null, $storeCarService->errors);
        return Helper::getResponse('Куплена машина');
    }
}
