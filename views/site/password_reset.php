<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap\ActiveForm $form */
/** @var app\forms\ChangePasswordForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Создание нового пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-password-reset">
    <div class="row">
        <div class="col-4"></div>

        <?php $form = ActiveForm::begin(['id' => 'password_reset_form', 'options' => ['class' => 'col-4']]); ?>

        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-block']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
