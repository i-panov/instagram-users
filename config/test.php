<?php

/**
 * Application configuration shared by all test types
 */
$config = require 'web.php';

$config['id'] = 'app-tests';
$config['components']['assetManager'] = ['basePath' => '@app/tests/_output/assets'];
$config['components']['request']['cookieValidationKey'] = 'test';
$config['components']['request']['enableCsrfValidation'] = false;

return $config;
