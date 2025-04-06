<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m250331_012516_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'email' => $this->string(120)->notNull(),
            'password' => $this->string(80)->notNull(),
            'access_token' => $this->string(80)->notNull(),
            'auth_key' => $this->string(80)->notNull(),
            'is_admin' => $this->boolean()->defaultValue(false),
            'accept_email' => $this->boolean()->defaultValue(false),
            'photo' => $this->string(80)->defaultValue(null),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'isDeleted' => $this->boolean()->defaultValue(false),
        ]);

        $user = [
            'name' => 'Super Administrador',
            'email' => 'admin@admin.com',
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'access_token' => Yii::$app->security->generateRandomString(80),
            'auth_key' => Yii::$app->security->generateRandomString(80),
            'is_admin' => true,
            'accept_email' => true,
        ];

        $this->insert('users', $user);

        $user2 = [
            'name' => 'Usuario Comum',
            'email' => 'usuario@comum.com',
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'access_token' => Yii::$app->security->generateRandomString(80),
            'auth_key' => Yii::$app->security->generateRandomString(80),
            'is_admin' => false,
            'accept_email' => false,
        ];

        $this->insert('users', $user2);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
