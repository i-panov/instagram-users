<?php

/* @var yii\web\View $this */
/* @var string $content */

use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\widgets\Alert;
use app\assets\AppAsset;

AppAsset::register($this);

if (!$this->title)
    $this->title = Yii::$app->name;

if (Yii::$app->user->isGuest) {
    $navItems[] = Html::tag('li', Html::a('Вход', ['site/login'], ['class' => 'btn btn-primary']), ['class' => 'nav-item']);
    $navItems[] = Html::tag('li', Html::a('Регистрация', ['site/signup'], ['class' => 'btn btn-primary']), ['class' => 'nav-item']);
} else {
    $logout = Html::a('&times;', ['site/logout'], [
        'class' => 'btn btn-link logout text-danger',
        'title' => 'Выход',
        'data' => ['confirm' => 'Вы уверены, что хотите выйти?', 'method' => 'post'],
    ]);

    $navItems[] = ['label' => Yii::$app->user->identity->email, 'url' => ['site/change-password'], 'options' => ['title' => 'Изменить пароль']];
    $navItems[] = Html::tag('li', $logout, ['class' => 'nav-item', 'style' => 'margin-left: -1.5rem']);
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="container bg-light">
    <?php $this->beginBody() ?>
        <header>
            <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => ['site/index'],
                'options' => ['class' => 'navbar navbar-expand-lg navbar-light'],
            ]);
                echo Nav::widget([
                     'options' => ['class' => 'nav ml-auto'],
                     'items' => $navItems,
                ]);
            NavBar::end();
            ?>
        </header>
        <main>
            <?php
            echo Breadcrumbs::widget([
                 'homeLink' => ['label' => 'Главная', 'url' => ['site/index']],
                 'links' => $this->params['breadcrumbs'] ?? [],
             ]);

            echo Alert::widget();
            echo sprintf('<h1 class="page-header text-center">%s</h1> <hr/>', Html::encode($this->title));
            echo $content;
            ?>
        </main>
        <footer>
            <div class="row text-center align-middle">
                <section class="col">&copy; My Company <?= date('Y') ?></section>
                <section class="col"><?= Yii::powered() ?></section>
            </div>
        </footer>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
