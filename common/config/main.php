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
        'adminWalletAddressBase58' => 'TM5JhqnbrFobs9ujSonwz5heTqXBU3Xkf1',
        'adminWalletAddressHex' => '4179d0a3e099d3aadbf4aef5a01440129b4b715381',
        'apiTronGrid' => 'https://api.shasta.trongrid.io'
    ],
];
