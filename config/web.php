<?php

$params = require __DIR__ . '/params.php';
$shared_components = require __DIR__ . '/shared.php';
$config = [
    'id' => 'app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['queue', 'log'],
    'aliases' => [],
    'language'   => 'ru-RU',
    'components' => \yii\helpers\ArrayHelper::merge($shared_components,[
        'request' => [
            'parsers'                => [
                'application/json'    => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
            ],
            'enableCookieValidation' => false,
        ],
        'response'=>[
          'format' => \yii\web\Response::FORMAT_JSON,
          'charset' => 'UTF-8'
        ],
        'user'       => [
            'identityClass' => 'app\models\Entity\User',
            'loginUrl'      => null,
            'enableSession' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false
        ]
    ]),
    'params' => $params,
];

return $config;
