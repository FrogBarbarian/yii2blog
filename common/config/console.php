<?php

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => '',
    'components' => [
        'db' => require __DIR__ . '/db.php',
        ],
];