<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provider_comments}}`.
 */
class m250405_234312_create_provider_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%provider_comments}}', [
            'id' => $this->primaryKey(),
            'provider_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'comment' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-provider_comment-provider_id',
            '{{%provider_comments}}',
            'provider_id',
            '{{%providers}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-provider_comment-user_id',
            '{{%provider_comments}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-provider_comment-provider_id', '{{%provider_comment}}');
        $this->dropForeignKey('fk-provider_comment-user_id', '{{%provider_comment}}');
        $this->dropTable('{{%provider_comments}}');
    }
}
