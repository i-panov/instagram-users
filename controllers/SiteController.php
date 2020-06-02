<?php

namespace app\controllers;

use app\forms\ChangePasswordForm;
use app\forms\ResetPasswordForm;
use app\forms\SignupForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\forms\LoginForm;

/**
 * Основной контроллер сайта
 */
class SiteController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['signup', 'login', 'request-password-reset', 'password-reset', 'signup-confirmation'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['errors'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['index', 'logout', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionSignup() {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User(['email' => $model->email]);
            $user->generateAccessToken();

            $messageWasSent = Yii::$app->mailer
                ->compose('signup_confirmation', ['user' => $user])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject('Подтверждение регистрации')
                ->send();

            if ($messageWasSent) {
                $user->setPassword($model->password);
                $user->save(false);
                Yii::$app->session->setFlash('success', 'Вы успешно зарегистрированы. Проверьте свою электронную почту для получения дальнейших инструкций.');
                return $this->redirect(['site/login']);
            }

            $model->addError('email', 'Не удалось отправить сообщение');
        }

        return $this->render('signup', ['model' => $model]);
    }

    public function actionSignupConfirmation($token) {
        $user = $this->findUserByAccessToken($token, User::SIGNUP_CONFIRMATION_TOKEN_EXPIRE,'Неверная ссылка подтверждения адреса электронной почты');
        $user->is_active = true;
        $user->removeAccessToken();
        $user->generateAuthKey();
        $user->save(false);
        $user->login();
        Yii::$app->session->setFlash('success', 'Email подтвержден.');
        return $this->redirect(['site/index']);
    }

    public function actionLogin() {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->user->login($model->rememberMe))
            return $this->redirect(['site/index']);

        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionRequestPasswordReset() {
        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->user->generateAccessToken();

            $messageWasSent = Yii::$app->mailer
                ->compose('password_reset', ['user' => $model->user])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setTo($model->email)
                ->setSubject('Сброс пароля')
                ->send();

            if ($messageWasSent) {
                $model->user->save(false);
                Yii::$app->session->setFlash('success', 'На вашу электронную почту была отправлена ссылка для сброса пароля.');
                return $this->redirect(['site/login']);
            }

            $model->addError('email', 'Не удалось отправить сообщение');
        }

        return $this->render('request_password_reset', ['model' => $model]);
    }

    public function actionPasswordReset($token) {
        $user = $this->findUserByAccessToken($token, User::PASSWORD_RESET_TOKEN_EXPIRE, 'Неверная ссылка сброса пароля');
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->setPassword($model->password);
            $user->removeAccessToken();
            $user->is_active = true;
            $user->save(false);
            Yii::$app->session->setFlash('success', 'Пароль изменен');
            return $this->redirect(['site/login']);
        }

        return $this->render('password_reset', ['model' => $model]);
    }

    public function actionChangePassword() {
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            $user->setPassword($model->password);
            $user->save(false);
            Yii::$app->session->setFlash('success', 'Пароль изменен');
            return $this->redirect(['profile/index']);
        }

        return $this->render('change_password', ['model' => $model]);
    }

    private function findUserByAccessToken($token, $expire, $message) {
        if (!User::isAccessTokenValid($token, $expire))
            throw new BadRequestHttpException($message);

        $user = User::findIdentityByAccessToken($token);

        if (!$user)
            throw new NotFoundHttpException('Пользователь не найден');

        return $user;
    }
}
