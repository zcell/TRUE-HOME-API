<?php

$params = require __DIR__ . '/params.php';
$shared_components = require __DIR__ . '/shared.php';

$config = [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['queue', 'log'],
    'language'   => 'ru-RU',
    'controllerNamespace' => 'app\commands',
    'aliases' => [],
    'components' => $shared_components,
    'params' => $params,
];

return $config;
