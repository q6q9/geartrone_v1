<?php

namespace frontend\module\api\v1\controllers;

use frontend\module\api\Helper;
use frontend\module\api\v1\services\UserService;

class BalanceController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $userService = new UserService(UserService::getSelf());
        return Helper::getResponse($userService->getUser()->TRX);
    }

    public function actionAdd($money){
        $userService = new UserService(UserService::getSelf());
        if ($userService->balance->add($money)){
            $userService->getUser()->save();
        }
        return Helper::getResponse('Добавлено');
    }
}