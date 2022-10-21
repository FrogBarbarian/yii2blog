<?php

const ADMIN_PANEL = '/admin';
const POSTS_ON_PAGE = 5;
const POSTS_ON_PROFILE = 5;
const IMAGES = '../../assets/images/';
const BASE_CACHE_TIME = 3600;
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
            'class' => yii\caching\FileCache::class,
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
                'admin/' => 'admin/index',
                'admin/<action>' => 'admin/<action>',
                '/<action>' => 'posts/<action>',
                'tag/<action>' => 'posts/tag',
                'author/<action>' => 'posts/author',
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