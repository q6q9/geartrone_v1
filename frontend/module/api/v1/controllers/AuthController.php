<?php

namespace frontend\module\api\v1\controllers;


use common\models\User;
use frontend\module\api\Helper;
use frontend\module\api\v1\models\AuthModel;
use frontend\module\api\v1\services\AuthService;
use frontend\module\api\v1\services\UserService;
use Yii;
use yii\httpclient\Client;
use yii\log\LogRuntimeException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    /**
     * @throws \yii\httpclient\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws BadRequestHttpException Если неверный адрес кошельа
     */
    public function actionRegisterOrLogin()
    {
        $userService = new UserService(new User());
        $userService->getUser()->address = Yii::$app->request->post('address');
        $userService->rank->setDown();

        $client = new Client();
        $response = $client->createRequest()
            ->setUrl(Yii::$app->params['apiTronGrid'] . '/v1/accounts/' . $userService->getUser()->address)
            ->setFormat(Response::FORMAT_JSON)
            ->setHeaders(['Content-Type' => 'application/json'])
            ->send();

        if (!$response->isOk) {
            throw new BadRequestHttpException('Неверный адрес кошелька в параметре \'address\'');
        }

        if (User::findOne(['address' => $userService->getUser()->address])){
            return Helper::getResponse('Successfully');
        }
        if ($userService->getUser()->save()){
            return Helper::getResponse('Successfully');
        }
        return Helper::getResponse(null, $userService->getUser()->errors);
    }
}
