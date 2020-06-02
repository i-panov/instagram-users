<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Пользователь
 *
 * @property int $id
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 * @property bool $is_active
 * @property int $created_at
 * @property int $updated_at
 *
 * @property-read InstagramUser[] $instagramUsers
 */
class User extends ActiveRecord implements IdentityInterface {
    public const PASSWORD_RESET_TOKEN_EXPIRE = 3600;
    public const SIGNUP_CONFIRMATION_TOKEN_EXPIRE = 3600 * 24;

    const EMAIL_MUST_EXIST_RULE = [
        'email', 'exist',
        'targetClass' => User::class,
        'filter' => ['is_active' => true],
        'message' => 'Пользователь с такой электронной почтой не зарегистрирован.'
    ];

    public static function tableName() {
        return 'users';
    }

    public function behaviors() {
        return [
            'timestamp' => TimestampBehavior::class,
            'attributeTypeCast' => [
                'class' => AttributeTypecastBehavior::class,
                'typecastAfterFind' => true,
            ],
        ];
    }

    public function rules() {
        return [
            ['email', 'required'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'trim'],
            ['email', 'email'],
            ['is_active', 'default', 'value' => false],
            ['is_active', 'boolean'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'is_active' => 'Активен',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    public function getId() {
        return $this->getPrimaryKey();
    }

    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'is_active' => true]);
    }

    public function validatePassword(string $password): bool {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword(string $password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    public function generateAuthKey() {
        $this->auth_key = $this->generateRandomString();
    }

    public static function isAccessTokenValid($token, $expire) {
        if (!$token || !is_string($token))
            return false;

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        return $timestamp + (int)$expire >= time();
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    public function generateAccessToken() {
        $this->access_token = $this->generateRandomString() . '_' . time();
    }

    public function removeAccessToken() {
        $this->access_token = null;
    }

    public function getInstagramUsers() {
        return $this->hasMany(InstagramUser::class, ['id' => 'instagram_user_id'])
            ->viaTable('users_instagram_users', ['user_id' => 'id']);
    }

    public function login($rememberMe = false) {
        return Yii::$app->user->login($this, $rememberMe ? 3600 * 24 * 30 : 0);
    }

    private function generateRandomString() {
        return Yii::$app->security->generateRandomString(32);
    }
}
