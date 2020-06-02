<?php
namespace app\forms;

use app\models\User;
use yii\base\Model;

/**
 * Форма регистрации
 */
class SignupForm extends Model {
    public $email, $password, $passwordRepeat;

    public function rules() {
        return [
            [['email', 'password', 'passwordRepeat'], 'required'],
            ['email', 'trim'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 4],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels() {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повтор пароля',
        ];
    }
}
