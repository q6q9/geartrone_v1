<?php

namespace frontend\module\api;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;

/**
 * api module definition class
 */
class Api extends \yii\base\Module
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $controllersNoAuthToken = ['auth'];
        if (!in_array(Yii::$app->controller->id, $controllersNoAuthToken)) {
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::class,
            ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $behaviors;
    }

    public $controllerNamespace = 'frontend\module\api\v1\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
