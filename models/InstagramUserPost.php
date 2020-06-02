<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Посты Instagram пользователя
 *
 * @property int $id
 * @property int $user_id
 * @property string $image_url
 * @property string $text
 *
 * @property-read InstagramUser $user
 */
class InstagramUserPost extends ActiveRecord {
    public static function tableName() {
        return 'instagram_user_posts';
    }

    public function rules() {
        return [
            ['user_id', 'required'],
            ['image_url', 'url'],
            ['text', 'string'],
        ];
    }

    public function attributeLabels() {
        return [
            'user_id' => 'ID Instagram пользователя',
            'image_url' => 'Ссылка на изображение',
            'text' => 'Текст',
        ];
    }

    public function beforeSave($insert) {
        // пришлось так сделать из-за смайликов, которые не хотели сохраняться в базу
        $this->text = base64_encode($this->text);
        return parent::beforeSave($insert);
    }

    public function afterFind() {
        parent::afterFind();
        $this->text = base64_decode($this->text);
    }

    public function getUser() {
        return $this->hasOne(InstagramUser::class, ['id' => 'user_id'])->inverseOf('posts');
    }
}
