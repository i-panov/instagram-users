<?php
namespace app\forms;

use yii\base\Model;
use app\models\User;

/**
 * Форма запроса на смену пароля
 *
 * @property-read User $user
 */
class ResetPasswordForm extends Model {
    public $email;

    public function rules() {
        return [
            [['email'], 'required'],
            ['email', 'email'],
            User::EMAIL_MUST_EXIST_RULE,
        ];
    }

    public function attributeLabels() {
        return [
            'email' => 'Email',
        ];
    }

    private $_user = false;

    public function getUser() {
        if ($this->_user === false)
            $this->_user = User::findOne(['email' => $this->email]);

        return $this->_user;
    }
}
