<?
return [
    'id' => 'yii2test',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/index',
    'bootstrap' => [
        'log',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'components' => [
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
                'main/<action>' => 'bad route',
                'register' => 'user/register',
                'profile' => 'profile/index',
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