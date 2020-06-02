<?php
/* @var yii\web\View $this */
/* @var app\forms\ChangePasswordForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Изменение пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-change-password row">
    <div class="col-4"></div>
    <div class="col-8">
        <div class="row">
            <p class="col">Введите новый пароль:</p>
        </div>
        <div class="row">
            <div class="col-5">
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
