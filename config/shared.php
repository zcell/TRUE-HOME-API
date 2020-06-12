<?php
$db = require __DIR__ . '/db.php';


return [
    'urlManagerMail'=>[
        'class'=>\yii\web\UrlManager::class,
        'baseUrl' => 'https://api.home.true-studio.ru/',
        'enablePrettyUrl' => true,
        'showScriptName' => false,
    ],
    'queue' => [
        'class' => \yii\queue\redis\Queue::class,
        'redis' => 'redis', // Redis connection component or its config
        'channel' => 'main_queue', // Queue channel key
    ],
    'cache' => [
        'class' => 'yii\redis\Cache',
        'redis' => [
            'hostname' => 'balancer',
            'port' => 6379,
            'database' => 0,
        ]
    ],
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => 'balancer',
        'port' => 6379,
        'database' => 0,
    ],
    'log' => [
        'traceLevel' => 3,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => true,
    ],
    'db' => $db,
];