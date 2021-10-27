<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'params' => [
        'adminWalletAddress' => 'TM5JhqnbrFobs9ujSonwz5heTqXBU3Xkf1',
        'apiTronGrid' => 'https://api.shasta.trongrid.io'
    ],
];
