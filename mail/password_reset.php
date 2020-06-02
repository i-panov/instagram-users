<?php
use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */

$resetUrl = Yii::$app->urlManager->createAbsoluteUrl(['site/password-reset', 'token' => $user->access_token]);
?>

<div>
    <h3>Здравствуйте!</h3>

    <p>
        Вы получили это письмо потому, что Вы (либо кто-то, выдающий себя за вас)
        попросили сбросить пароль к вашей учётной записи на сайте
        <?= Html::a(Yii::$app->name, Yii::$app->urlManager->createAbsoluteUrl(['site/index'])) ?>.
        Если Вы не просили выслать пароль, не обращайте внимания на это письмо.
    </p>

    <p>
        Для установки нового пароля перейдите по ссылке:
        <?= Html::a($resetUrl, $resetUrl) ?>
    </p>
</div>
