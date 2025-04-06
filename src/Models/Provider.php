<?php

namespace App\Models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "providers".
 *
 * @property int $id
 * @property string $name
 * @property string|null $service_description
 * @property string|null $logo
 * @property int $service_type_id
 * @property int $user_id
 * @property string|null $phone
 * @property int|null $accept_email
 * @property string|null $mobile_fone
 * @property string|null $contact_name
 * @property string|null $contact_phone
 * @property string|null $instagram
 * @property string|null $website
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Comment[] $comments
 * @property ServiceType $serviceType
 */
class Provider extends \yii\db\ActiveRecord
{

    const EVENT_AFTER_PROVIDER_CREATED = 'afterProviderCreated';

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'providers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_description', 'phone', 'mobile_fone', 'contact_name', 'contact_phone', 'instagram', 'website'], 'default', 'value' => null],
            [['logo'], 'default', 'value' => null],
            [['logo'], 'image', 'extensions' => 'png, jpg, jpeg'],
            [['name', 'service_type_id'], 'required'],
            [['service_description'], 'string'],
            [['service_type_id', 'accept_email', 'user_id'], 'integer'],
            [['name', 'logo', 'phone', 'mobile_fone', 'contact_name', 'contact_phone'], 'string', 'max' => 255],
            [['service_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServiceType::class, 'targetAttribute' => ['service_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['name', 'service_type_id', 'user_id'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nome',
            'service_description' => 'Descrição do Serviço',
            'logo' => 'Logo',
            'service_type_id' => 'Tipo de Serviço',
            'phone' => 'Telefone',
            'mobile_fone' => 'Celular',
            'contact_name' => 'Nome do vendedor',
            'contact_phone' => 'Telefone do vendedor',
            'created_at' => 'Criado em',
            'updated_at' => 'Alterado em',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return ActiveQuery|\App\Models\Query\CommentQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['provider_id' => 'id']);
    }

    /**
     * Gets query for [[ServiceType]].
     *
     * @return ActiveQuery|\App\Models\Query\ServiceTypeQuery
     */
    public function getServiceType()
    {
        return $this->hasOne(ServiceType::class, ['id' => 'service_type_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \App\Models\Query\ProviderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \App\Models\Query\ProviderQuery(get_called_class());
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->user_id = Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }

    public function getPhotos()
    {
        return $this->hasMany(ProviderPhoto::class, ['provider_id' => 'id']);
    }

    public function canEdit(): bool
    {
        $user = Yii::$app->user;
        return $user->id === $this->user_id || $user->identity->isAdmin;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->trigger(self::EVENT_AFTER_PROVIDER_CREATED);
        }
    }

}
