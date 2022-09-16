<?php

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '/../cache',
            ],
        'db' => require __DIR__ . '/db.php',
        ],
];