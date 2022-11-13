<?php

require __DIR__ . '/constants.php';

return [
    'id' => 'yii2test',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'post/index',
    'bootstrap' => [
        'log',
    ],
    'aliases' => [
        '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
        '@js' => '/assets/js',
        '@css' => '/assets/css',
    ],
    'params' => require __DIR__ . '/params.php',
//    'timeZone' => 'Europe/Moscow',
    'components' => [
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '', //Хост
                'username' => '', //Имя пользователя для логина на почтовом хосте
                'password' => '', //Пароль для логина на почтовом хосте
                'port' => '465', //Порт хоста
                'encryption' => 'ssl', //Тип шифрования
            ],
        ],
        'request' => [
            'baseUrl' => '',
            'cookieValidationKey' => 'Mgw2YR5XFMuUXvBkNHQ0qwYeKJiDsQ',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@runtime/cache',
        ],
        'db' => require __DIR__ . '/db.php',
        'urlManager' => [
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require __DIR__ . '/routes.php',
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
