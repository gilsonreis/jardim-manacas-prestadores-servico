<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provider_photos}}`.
 */
class m250405_030739_create_provider_photos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%provider_photos}}', [
            'id' => $this->primaryKey(),
            'provider_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'path' => $this->string()->notNull(),
            'thumb' => $this->string()->notNull(),
            'description' => $this->text()->defaultValue(null),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-provider_photos-provider',
            '{{%provider_photos}}',
            'provider_id',
            '{{%providers}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-provider_photos-user',
            '{{%provider_photos}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-provider_photos-provider', '{{%provider_photos}}');
        $this->dropForeignKey('fk-provider_photos-user', '{{%provider_photos}}');
        $this->dropTable('{{%provider_photos}}');
    }
}
