<?php

use yii\db\Migration;

class m250331_021427_insert_service_types_into_service_types_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $timestamp = date('Y-m-d H:i:s');

        $this->batchInsert('{{%service_types}}', ['name', 'created_at', 'updated_at'], [
            ['Eletricista', $timestamp, $timestamp],
            ['Encanador', $timestamp, $timestamp],
            ['Marceneiro', $timestamp, $timestamp],
            ['Vidraceiro', $timestamp, $timestamp],
            ['Instalação de Box', $timestamp, $timestamp],
            ['Instalação de Gesso', $timestamp, $timestamp],
            ['Pintor', $timestamp, $timestamp],
            ['Instalação de Piso Laminado', $timestamp, $timestamp],
            ['Instalação de Porcelanato', $timestamp, $timestamp],
            ['Pedreiro', $timestamp, $timestamp],
            ['Ar-condicionado', $timestamp, $timestamp],
            ['Serralheiro', $timestamp, $timestamp],
            ['Instalação de Móveis Planejados', $timestamp, $timestamp],
            ['Montador de Móveis', $timestamp, $timestamp],
            ['Instalação de Iluminação', $timestamp, $timestamp],
            ['Colocação de Papel de Parede', $timestamp, $timestamp],
            ['Designer de Interiores', $timestamp, $timestamp],
            ['Limpeza Pós-Obra', $timestamp, $timestamp],
            ['Consultoria de Obras', $timestamp, $timestamp],
            ['Serviços Gerais', $timestamp, $timestamp],
            ['Chaveiro', $timestamp, $timestamp],
            ['Instalador Casa Inteligente', $timestamp, $timestamp],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%service_types}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250331_021427_insert_service_types_into_service_types_tables cannot be reverted.\n";

        return false;
    }
    */
}
