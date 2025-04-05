<?php

namespace App\Models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $access_token
 * @property int|null $is_admin
 * @property string|null $photo
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $isDeleted
 *
 * @property Comment[] $comments
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public ?string $currentPassword = null;
    public ?string $repeatPassword = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isDeleted'], 'default', 'value' => 0],
            [['photo'], 'default', 'value' => 'no-image.png'],
            [['name', 'email', 'password', 'access_token'], 'required'],
            [['is_admin', 'isDeleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 120],
            [['password', 'access_token', 'photo'], 'string', 'max' => 80],
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
            'email' => 'E-mail',
            'password' => 'Senha',
            'access_token' => 'Access Token',
            'is_admin' => 'Administrador',
            'photo' => 'Foto',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery|\App\Models\Query\CommentQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \App\Models\Query\UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \App\Models\Query\UsersQuery(get_called_class());
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException();
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key ?? "";
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException();
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->getAttribute('password'));
    }

    public static function findByUserName($username)
    {
        return static::find()
            ->where(['email' => $username])
            ->one();
    }

    public function afterFind()
    {
        $this->currentPassword = $this->password;
    }

    public function getIsAdmin(): bool
    {
        return $this->is_admin === 1;
    }

}
