<?php

namespace app\controllers;

use app\models\InstagramUser;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class InstagramUsersController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list', 'add', 'remove', 'posts'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionList() {
        return $this->asJson($this->getCurrentUser()->instagramUsers);
    }

    public function actionAdd() {
        $username = \Yii::$app->request->post('username');
        $instagramUser = InstagramUser::findOne(['name' => $username]) ?? InstagramUser::create($username);
        $this->getCurrentUser()->link('instagramUsers', $instagramUser);
        return $this->asJson($instagramUser);
    }

    public function actionRemove($id) {
        $model = $this->findModel($id);
        $this->getCurrentUser()->unlink('instagramUsers', $model, true);
        return $this->asJson(true);
    }

    public function actionPosts($id) {
        $model = $this->findModel($id);
        return $this->asJson($model->actualPosts);
    }

    private function getCurrentUser(): ?User {
        return \Yii::$app->user->identity;
    }

    private function findModel($id) {
        $model = InstagramUser::findOne($id);

        if (!$model)
            throw new NotFoundHttpException("Instagram пользователь #$id не найден");

        return $model;
    }
}
