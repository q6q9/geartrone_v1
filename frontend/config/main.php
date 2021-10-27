<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
            'api' => [
                'class' => 'frontend\module\api\Api',
            ],
        ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
//            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET api/users/<id:\d+>' => 'api/users/view',
                'PATCH api/users/' => 'api/users/patch',
                'POST api/balance/add/<money:\d+>' => 'api/balance/add',

                'POST api/cars/get-transaction/<id:\d+>' => 'api/cars/get-transaction',
                'POST api/cars/buy/<id:\d+>' => 'api/cars/buy',

                'GET api/cars/garage/<id:\d+>' => 'api/cars/garage'
            ],
        ],
//        */
    ],
    'params' => $params,
];
