<?php

const ADMIN_PANEL = '/admin';
const POSTS_ON_PAGE = 5;
const IMAGES = '/assets/images/';
const UPLOAD_IMAGES = '/uploads/';
const BASE_CACHE_TIME = 3600;
const USER_LOGIN = '/user/login';
const USER_REGISTER = '/user/register';
//TODO: Организовать хранение констант

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
    'params' => ['bsDependencyEnabled' => false],
//    'timeZone' => 'Europe/Moscow',
    'components' => [
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
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