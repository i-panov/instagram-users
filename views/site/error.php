<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br($message) ?>
    </div>

    <p>Ошибка произошла когда обрабатывался ваш запрос. Если она будет повторяться свяжитесь с нами.</p>
</div>
