<?php

declare(strict_types=1);

use yii\web\Application;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

error_reporting(E_ALL & ~E_DEPRECATED);

$config = require dirname(__DIR__) . '/config/main.php';

$app = new Application($config);
$app->db->open();
$app->run();
