<?php

namespace App\Models;

use Yii;

/**
 * This is the model class for table "service_types".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Provider[] $providers
 */
class ServiceType extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Providers]].
     *
     * @return \yii\db\ActiveQuery|\App\Models\Query\ProviderQuery
     */
    public function getProviders()
    {
        return $this->hasMany(Provider::class, ['service_type_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \App\Models\Query\ServiceTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \App\Models\Query\ServiceTypeQuery(get_called_class());
    }

}
