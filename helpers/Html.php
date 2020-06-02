<?php

namespace yii\helpers;

use yii\web\Request;

class Html extends \yii\helpers\BaseHtml {
    public static function meta($name = null, $content = null, $charset = null, $httpEquiv = null) {
        return static::tag('meta', '', array_filter(['name' => $name, 'content' => $content, 'charset' => $charset, 'http-equiv' => $httpEquiv]));
    }

    public static function csrfMetaTags() {
        $request = \Yii::$app->getRequest();

        if ($request instanceof Request && $request->enableCsrfValidation) {
            return implode(PHP_EOL, [
                static::meta('csrf-header-param', Request::CSRF_HEADER),
                static::meta('csrf-param', $request->csrfParam),
                static::meta('csrf-token', $request->getCsrfToken()),
            ]);
        }

        return '';
    }
}
