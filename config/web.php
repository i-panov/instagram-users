<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'app-web',
    'name' => 'Instagram пользователи',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '_tu886ZQymMn93ahvhEzZ67LeQMGBuF7',
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => YII_ENV_PROD ? \yii\caching\FileCache::class : \yii\caching\DummyCache::class,
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => \app\models\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
        'db' => require 'db.php',
        'urlManager' => [
            'class' => \yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'signup' => 'site/signup',
                'signup/confirmation' => 'site/signup-confirmation',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'password/reset/request' => 'site/request-password-reset',
                'password/reset' => 'site/password-reset',
                'password/change' => 'site/change-password',

                'instagram-users' => 'instagram-users/list',
                'POST instagram-users/add' => 'instagram-users/add',
                'DELETE instagram-users/remove' => 'instagram-users/remove',
            ],
        ],
        'formatter' => [
            'dateFormat'     => 'php:d.m.Y',
            'datetimeFormat' => 'php:d.m.Y, H:i',
            'timeFormat'     => 'php:H:i',
            'thousandSeparator' => ' ',
        ],
    ],
    'params' => require 'params.php',
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'historySize' => 500,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
