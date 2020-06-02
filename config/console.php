<?php

$config = require 'web.php';
unset($config['components']['errorHandler']);
unset($config['components']['request']);

$config['id'] = 'app-console';
$config['controllerNamespace'] = 'app\commands';
$config['aliases']['@tests'] = '@app/tests';
$config['components']['cache'] = \yii\caching\DummyCache::class;

$config['controllerMap'] = [
    'fixture' => 'yii\faker\FixtureController',
];

return $config;
