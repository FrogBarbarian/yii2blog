<?php

return [
    'id' => 'main',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => '/',
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '/../cache',
            ],
        'db' => require __DIR__ . '/db.php',
        'urlManager' => [
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => '/index',
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