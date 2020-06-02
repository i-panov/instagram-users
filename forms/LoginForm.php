<?php
namespace app\forms;

use yii\base\Model;
use app\models\User;

/**
 * Форма входа
 *
 * @property-read User $user
 */
class LoginForm extends Model {
    public $email, $password, $rememberMe;

    public function rules() {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            User::EMAIL_MUST_EXIST_RULE,
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
            ['rememberMe', 'default', 'value' => false],
        ];
    }

    public function attributeLabels() {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    public function validatePassword($attribute, $params) {
        if (!($this->user && $this->user->validatePassword($this->password)))
            $this->addError($attribute, 'Неверный пароль');
    }

    private $_user = false;

    public function getUser() {
        if ($this->_user === false)
            $this->_user = User::findOne(['email' => $this->email]);

        return $this->_user;
    }
}
