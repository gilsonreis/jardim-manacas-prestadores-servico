<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provider}}`.
 */
class m250331_020515_create_provider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%providers}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'service_description' => $this->text()->defaultValue(null),
            'logo' => $this->string()->defaultValue(null),
            'service_type_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'phone' => $this->string()->defaultValue(null),
            'accept_email' => $this->boolean()->defaultValue(false),
            'mobile_fone' => $this->string()->defaultValue(null),
            'contact_name' => $this->string()->defaultValue(null),
            'contact_phone' => $this->string()->defaultValue(null),
            'instagram' => $this->string()->defaultValue(null),
            'website' => $this->string()->defaultValue(null),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

         $this->addForeignKey(
             'fk-provider-service_type',
             '{{%providers}}',
             'service_type_id',
             '{{%service_types}}',
             'id',
             'CASCADE',
             'CASCADE'
         );

        $this->addForeignKey(
            'fk-provider-users',
            '{{%providers}}',
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
        $this->dropForeignKey('fk-provider-service_type', '{{%providers}}');
        $this->dropForeignKey('fk-provider-users', '{{%providers}}');
        $this->dropTable('{{%providers}}');
    }
}
