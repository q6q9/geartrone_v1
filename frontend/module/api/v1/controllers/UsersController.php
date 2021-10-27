<?php

namespace frontend\module\api\v1\controllers;

use common\models\User;
use frontend\module\api\Helper;
use frontend\module\api\v1\services\UserService;
use Yii;
use yii\web\Controller;

class UsersController extends Controller
{

    public function actionIndex()
    {
        $users = User::find()->all();
        return Helper::getResponse(['users' => $users, 'count' => count($users)]);
    }

    public function actionView($id)
    {
        return Helper::getResponse(User::findAll($id));
    }

    public function actionSelf()
    {
        return UserService::getSelf();
    }

    public function actionPatch()
    {
    }

    public function actionDelete($id)
    {
        $user = User::findAll(['id' => $id]);
        if ($user[0]->delete() === false) {
            return ['data' => null,
                'errors' => ['validation' => $user->errors]
            ];
        }
        return ['data' => 'Success',
            'errors' => null
        ];
    }

}
