<?php

namespace App\Models\Forms;

use App\Models\User;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\imagine\Image;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

class ProfileForm extends Model
{
    public string $name;
    public string $email;
    public ?string $password = null;
    public ?string $repeatPassword = null;
    public ?string $currentPassword = null;
    public int $accept_email;
    public $photo;
    public ?string $photoName = '';

    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'As senhas não coincidem.'],
            ['currentPassword', 'validateCurrentPassword'],
            ['accept_email', 'boolean'],
            ['photo', 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 1024 * 2],
        ];
    }

    public function validateCurrentPassword()
    {
        if (!empty($this->password)) {
            $user = Yii::$app->user->identity;
            if (!$user || !Yii::$app->security->validatePassword($this->currentPassword, $user->password)) {
                $this->addError('currentPassword', 'Senha atual incorreta.');
            }
        }
    }

    public function save(): bool
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->accept_email = $this->accept_email;

        if (!empty($this->password)) {
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
        }

        $this->photo = UploadedFile::getInstance($this, 'photo');

        if ($this->photo instanceof UploadedFile) {
            if (!empty($this->photoName)) {
                $oldFile = Yii::getAlias('@webroot') . '/uploads/users/' . $this->photoName;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $fileName = 'user_' . Yii::$app->user->id . '.' . $this->photo->extension;
            $filePath = Yii::getAlias('@webroot') . '/uploads/users/';

            FileHelper::createDirectory($filePath);

            $filePath .= $fileName;

            $image = Image::thumbnail($this->photo->tempName, 300, 300);
            $image->save($filePath, ['quality' => 85]);
            $user->photo = '/uploads/users/' . $fileName;
        } else {
            $user->photo = $this->photoName;
        }

        return $user->save(false);
    }

    public function loadFromUser(IdentityInterface $user): void
    {
        $this->photo = null;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->accept_email = (int) $user->accept_email;
        $this->photoName = $user->photo;
    }
}