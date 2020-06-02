<?php

/** @var yii\web\View $this */
/** @var app\forms\LoginForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <div class="row">
        <div class="col-4"></div>

        <?php $form = ActiveForm::begin(['id' => 'login_form', 'options' => ['class' => 'col-4']]); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="row">
            <div class="col">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <div class="col">
                <?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
