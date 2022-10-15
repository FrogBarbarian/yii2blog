<?php

const ADMIN_PANEL = '/admin/panel';
const POSTS_ON_PAGE = 5;
const POSTS_ON_PROFILE = 5;
const IMAGES = '../../assets/images/';
//TODO: Организовать хранение констант

return [
    'id' => 'yii2test',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/index',
    'bootstrap' => [
        'log',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'components' => [
        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'request' => [
            'baseUrl' => '',
            'cookieValidationKey' => 'Mgw2YR5XFMuUXvBkNHQ0qwYeKJiDsQ',
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
                '/' => 'posts/index',
                '/register' => 'users/register',
                '/login' => 'users/login',
                '/profile' => 'users/user',
                '/user' => 'users/user',
                'admin/panel' => 'admin/index',
                'admin/panel/<action>' => 'admin/<action>',
                '/<action>' => 'posts/<action>',
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