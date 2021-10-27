<?php

namespace frontend\module\api\v1\controllers;

use frontend\module\api\Helper;
use frontend\module\api\v1\services\UserService;
use yii\web\Controller;

class GaragesController extends Controller
{
    public function actionIndex()
    {
        $userService = new UserService(UserService::getSelf());
        return Helper::getResponse($userService->garage->all());
    }
}
