<?php

namespace App\Models;

use Yii;

/**
 * This is the model class for table "provider_photos".
 *
 * @property int $id
 * @property int $provider_id
 * @property int $user_id
 * @property string $path
 * @property string $thumb
 * @property string|null $description
 * @property string $created_at
 *
 * @property User $user
 * @property Provider $provider
 */
class ProviderPhoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'provider_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description'], 'default', 'value' => null],
            [['provider_id', 'user_id'], 'required'],
            [['provider_id', 'user_id'], 'integer'],
            [['description'], 'string'],
            [['created_at'], 'safe'],
            [['path', 'thumb'], 'string', 'max' => 255],
            [['provider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::class, 'targetAttribute' => ['provider_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['provider_id', 'user_id'], 'safe'],
//            [['path'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxFiles' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'provider_id' => 'Prestador',
            'user_id' => 'Usuário',
            'path' => 'Imagem',
            'thumb' => 'Miniatura',
            'description' => 'Descrição',
            'created_at' => 'Criado em',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\App\Models\Query\UsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Provider]].
     *
     * @return \yii\db\ActiveQuery|\App\Models\Query\ProvidersQuery
     */
    public function getProvider()
    {
        return $this->hasOne(Provider::class, ['id' => 'provider_id']);
    }

    /**
     * {@inheritdoc}
     * @return \App\Models\Query\ProviderPhotosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \App\Models\Query\ProviderPhotosQuery(get_called_class());
    }

}
