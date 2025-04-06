<?php

namespace App\Models;

use Yii;

/**
 * This is the model class for table "provider_comments".
 *
 * @property int $id
 * @property int $provider_id
 * @property int $user_id
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Providers $provider
 * @property Users $user
 */
class ProviderComments extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provider_comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['provider_id', 'user_id', 'comment'], 'required'],
            [['provider_id', 'user_id'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['provider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::class, 'targetAttribute' => ['provider_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provider_id' => 'Provider ID',
            'user_id' => 'User ID',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\App\Models\Query\UsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \App\Models\Query\ProviderCommentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \App\Models\Query\ProviderCommentsQuery(get_called_class());
    }

}
