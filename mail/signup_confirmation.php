<?php
use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */

$confirmUrl = Yii::$app->urlManager->createAbsoluteUrl(['site/signup-confirmation', 'token' => $user->access_token]);
?>

<div>
    <h3>Здравствуйте!</h3>

    <p>
        Добро пожаловать на сайт <?= Html::a(Yii::$app->name, Yii::$app->urlManager->createAbsoluteUrl(['site/index'])) ?>! <br/>
    </p>

    <p>
        Ваша учётная запись пока не активна. Для активации перейдите по следующей ссылке:
        <?= Html::a($confirmUrl, $confirmUrl) ?>
    </p>

    <p>
        Не забывайте свой пароль: он хранится в нашей базе в зашифрованном виде, и мы не сможем вам его выслать.
        Если Вы всё же забудете свой пароль, то Вы сможете запросить новый, который будет активирован таким же образом, как и Ваша учётная запись.
        Спасибо за регистрацию.
    </p>

    <p>Если произошло недоразумение и Вы не регистрировались, то просто удалите это письмо, не отвечая на него.</p>
</div>
