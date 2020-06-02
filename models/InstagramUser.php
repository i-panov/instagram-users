<?php

namespace app\models;

use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Instagram пользователь
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 *
 * @property-read InstagramUserPost[] $posts
 * @property-read InstagramUserPost[] $actualPosts
 *
 * @method void touch(string $attribute)
 */
class InstagramUser extends ActiveRecord {
    public static function tableName() {
        return 'instagram_users';
    }

    public function fields() {
        return array_merge(parent::fields(), [
            'actualPosts' => function(self $model) {
                return $model->actualPosts;
            },
        ]);
    }

    public function behaviors() {
        return [
            'timestamp' => TimestampBehavior::class,
        ];
    }

    public function rules() {
        return [
            ['name', 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => 'Имя',
        ];
    }

    public function getPosts() {
        return $this->hasMany(InstagramUserPost::class, ['user_id' => 'id'])->inverseOf('user');
    }

    public function getActualPosts() {
        // обновление каждые 10 минут
        if (time() > ($this->updated_at + 60 * 10)) {
            $this->unlinkAll('posts', true);
            $this->addTop10Posts(static::fetchRawPosts($this->name));
            $this->touch('updated_at');
        }

        return $this->posts;
    }

    public static function fetchRawData($username) {
        if (!$username)
            throw new BadRequestHttpException('Имя Instagram пользователя было пустым.');

        try {
            $json = file_get_contents("https://www.instagram.com/${username}/?__a=1");
        } catch (ErrorException $e) {
            throw new NotFoundHttpException("Instagram пользователь \"$username\" не найден.");
        }

        return Json::decode($json);
    }

    public static function fetchRawPosts($username) {
        $data = static::fetchRawData($username);
        $edges = ArrayHelper::getValue($data, 'graphql.user.edge_owner_to_timeline_media.edges', []);
        return ArrayHelper::map($edges, 'node.display_url', 'node.edge_media_to_caption.edges.0.node.text');
    }

    public static function create($username) {
        $fetchedPosts = static::fetchRawPosts($username);
        $model = new static(['name' => $username]);
        $model->save(false);
        $model->addTop10Posts($fetchedPosts);
        return $model;
    }

    public function addTop10Posts($fetchedPosts) {
        foreach (array_slice($fetchedPosts, 0, 10, true) as $image_url => $text)
            $this->link('posts', new InstagramUserPost(['image_url' => $image_url, 'text' => $text]));
    }
}
