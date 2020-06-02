<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200528_121107_init
 */
class m200528_121107_init extends Migration
{
    public function safeUp() {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(50)->unique(),
            'access_token' => $this->string(50)->unique(),
            'is_active' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('instagram_users', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('instagram_user_posts', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'image_url' => $this->string(),
            'text' => $this->text(),
        ]);

        $this->addFK('instagram_user_posts', 'user_id', 'instagram_users', 'id');

        $this->createTable('users_instagram_users', [
            'user_id' => $this->integer()->notNull(),
            'instagram_user_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-users_instagram_users', 'users_instagram_users', ['user_id', 'instagram_user_id']);
        $this->addFK('users_instagram_users', 'user_id', 'users', 'id');
        $this->addFK('users_instagram_users', 'instagram_user_id', 'instagram_users', 'id');
    }

    public function safeDown() {
        $tables = (new Query())
            ->from('information_schema.tables')
            ->where("table_schema = schema() and table_type = 'base table' and table_name != 'migration'")
            ->select('table_name')
            ->column();

        $this->db->createCommand()->checkIntegrity(false)->execute();
        array_walk($tables, [$this, 'dropTable']);
        $this->delete('migration', 'version != "m000000_000000_base"');
        $this->db->createCommand()->checkIntegrity(true)->execute();
    }

    private function addFK($table, $column, $refTable, $refColumn, $onDelete = 'CASCADE') {
        $this->addForeignKey(
            "fk-$table-$column-$refTable-$refColumn",
            $table,
            $column,
            $refTable,
            $refColumn,
            $onDelete
        );
    }
}
