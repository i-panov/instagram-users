<?php
namespace app\forms;

use yii\base\Model;

/**
 * Форма смены пароля
 */
class ChangePasswordForm extends Model {
    public $password, $passwordRepeat;

    public function rules() {
        return [
            [['password', 'passwordRepeat'], 'required'],
            ['password', 'string', 'min' => 4],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels() {
        return [
            'password' => 'Пароль',
            'passwordRepeat' => 'Повтор пароля',
        ];
    }
}
