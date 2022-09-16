<?php

return [
    'id' => 'main',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/index',
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
    ],
];