<?php

const ADMIN_PANEL = '/admin';
const POSTS_ON_PAGE = 5;
const IMAGES = '../web/assets/images/';
const BASE_CACHE_TIME = 3600;
const USER_LOGIN = 'user/login';
const USER_REGISTER = 'user/register';
//TODO: Организовать хранение констант

return [
    'id' => 'yii2test',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'posts/index',
    'bootstrap' => [
        'log',
    ],
    'aliases' => [
        '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
        '@js' => 'web/assets/js',
        '@css' => 'web/assets/css',
        '@images' => 'web/assets/images',
    ],
    'timeZone' => 'Europe/Moscow',
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