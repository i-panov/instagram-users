<?php

/** @var yii\web\View $this */
/** @var yii\data\DataProviderInterface $executorInfoItemsDataProvider */

use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->registerJsVar('instagramUsersListUrl', Url::to(['instagram-users/list']));
$this->registerJsVar('instagramUserPostsUrl', Url::to(['instagram-users/posts']));
$this->registerJsVar('instagramUsersAddUrl', Url::to(['instagram-users/add']));
$this->registerJsVar('instagramUsersRemoveUrl', Url::to(['instagram-users/remove']));

$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue/dist/vue.js');
$this->registerJsFile('/js/instagram-users.js', ['depends' => [\app\assets\AppAsset::class]]);
?>

<div id="app" class="container">
    <div class="row add-user">
        <div class="col form-group">
            <input v-model="username" class="form-control" />
        </div>
        <div class="col form-group">
            <button v-on:click="addUser" class="btn btn-success">Добавить пользователя</button>
        </div>
    </div>
    <div class="users">
        <div v-for="user in users">
            <div class="row">
                <div class="col user">
                    <span v-on:click="removeUser(user.id)" title="Удалить" class="remove-user">&times;</span>&nbsp;<b class="user-name">{{ user.name }}</b>
                </div>
            </div>
            <div class="row user-posts">
                <div class="col">
                    <div v-for="post in user.actualPosts" class="row user-post">
                        <div class="col-2 user-post-image">
                            <a :href="post.image_url" target="_blank"><img :src="post.image_url" /></a>
                        </div>
                        <div class="col-10 user-post-text">
                            {{ post.text }}
                        </div>
                    </div>
                </div>
            </div>
            <hr />
        </div>
    </div>
</div>

<style>
    .user {
        font-size: 8vh;
        margin-bottom: 1rem;
    }

    .remove-user {
        cursor: pointer;
    }

    .user-post {
        margin-bottom: 1rem;
    }

    .user-post-image img {
        width: 10rem;
        height: 10rem;
        float: left;
    }

    .user-post-text {
        height: 10rem;
        overflow-y: auto;
        word-wrap: break-word;
    }
</style>
