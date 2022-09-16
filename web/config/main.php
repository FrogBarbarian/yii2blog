<?php

return [
    'id' => 'main',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/index',
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'components' => [
        'request' => [
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => dirname(__DIR__, 2) . '/cache',
            ],
        'db' => require __DIR__ . '/db.php',
        'urlManager' => [
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<action>' => 'main/<action>',
                '/' => 'main/index',
                '<controller>/<action>' => '<controller>/<action>',
            ],
        ],
        'log' => [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
            'categories' => [
                'yii\db\*',
                'yii\web\HttpException:*',
                ],
            ],
        ],
    ];