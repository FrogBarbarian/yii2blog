<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

//Composer autoload
require dirname(__DIR__) . '/vendor/autoload.php';

//Yii
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

error_reporting(E_ALL & ~E_DEPRECATED);

$config = require 'config/main.php';

$app = new \yii\web\Application($config);
$app->db->open();

$app->run();
