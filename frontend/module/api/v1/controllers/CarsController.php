<?php

namespace frontend\module\api\v1\controllers;

use Cassandra\Exception\TimeoutException;
use common\models\Car;
use common\models\Garage;
use frontend\module\api\Helper;
use frontend\module\api\RawData;
use frontend\module\api\v1\services\StoreCarService;
use frontend\module\api\v1\services\UserService;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Tron;
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
    public function actionGetTransaction($id): array
    {
        if (!Yii::$app->request->isPost)
            return Helper::getResponse(null, 'Only POST request can buy', 400);
        if (!$car = Car::findOne($id))
            return Helper::getResponse(null, 'Несуществующая машина', 400);

        $apiUrl = new HttpProvider(Yii::$app->params['apiTronGrid']);
        $tron = new Tron($apiUrl, $apiUrl, $apiUrl);

        $fromAddressHex = $tron->address2HexString(explode(' ', Yii::$app->request->headers['authorization'])[1]);

        $transaction = $tron->getTransactionBuilder()->sendTrx(
            Yii::$app->params['adminWalletAddressHex'],
            $car->price,
            $fromAddressHex
        );
        return Helper::getResponse($transaction);
    }


    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @throws BadRequestHttpException
     */
    public function actionSendSignTransaction($id): array
    {
        if (!$signedTransaction = Yii::$app->request->post('signed_transaction')) {
            throw new BadRequestHttpException('Not exist POST param \'signed_transaction\'');
        }
        if (!$car = Car::findOne($id))
            return Helper::getResponse(null, 'Несуществующая машина', 400);

        $userService = new UserService(UserService::getSelf());
        if ($userService->car->has($car)){
            return Helper::getResponse(null, 'Машина уже куплена', 400);
        }

        $rawData = (RawData::createFromSignedTransaction(json_decode($signedTransaction, true)));
        if (!$rawData->HasRightOwnerAddress()) {
            throw new BadRequestHttpException('Incorrect to_address');
        }
        if ($rawData->amount / 1000000 !== $car->price) {
            throw new BadRequestHttpException('Incorrect amount');
        }

        $apiUrl = new HttpProvider(Yii::$app->params['apiTronGrid']);
        $tron = new Tron($apiUrl, $apiUrl, $apiUrl);

        $result = $tron->sendRawTransaction(json_decode($signedTransaction, true));
        if (!isset($result['result']) or !$result['result'] != true) {
            return Helper::getResponse(null, $result, '400');
        }

        $storeCarService = new StoreCarService($userService, $car);
        if (!$storeCarService->buyCar())
            return Helper::getResponse(null, $storeCarService->errors);
        return Helper::getResponse('Куплена машина');
    }
}
