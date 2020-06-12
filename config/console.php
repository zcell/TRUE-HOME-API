<?php

use app\models\Integration\S3;

$params = require __DIR__ . '/params.php';
$shared_components = require __DIR__ . '/shared.php';

$config = [
    'id'                  => 'console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['queue', 'log'],
    'language'            => 'ru-RU',
    'controllerNamespace' => 'app\commands',
    'aliases'             => [],
    'components'          => \yii\helpers\ArrayHelper::merge(
        [
            's3' => array_merge(
                ['class' => S3::class],
                $params['s3']
            ),
        ],
        $shared_components
    ),
    'params'              => $params,
];

return $config;
