<?php
namespace App\Models\Forms;

use App\Models\User;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

class RegisterForm extends Model
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $repeatPassword = '';
    public int $accept_email = 0;

    public function rules(): array
    {
        return [
            [['name', 'email', 'password', 'repeatPassword'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => \App\Models\User::class, 'message' => 'Este email não está disponível para cadastro.'],
            [['password', 'repeatPassword'], 'string', 'min' => 6],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'As senhas não coincidem.'],
            ['accept_email', 'boolean'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Nome',
            'email' => 'Email',
            'password' => 'Senha',
            'repeatPassword' => 'Confirme a Senha',
            'accept_email' => 'Quero receber emails sobre novos prestadores',
        ];
    }

    public function register(): ?User
    {
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->accept_email = $this->accept_email;
        $user->is_admin = 0;
        $user->photo = null;
        $user->access_token = Yii::$app->security->generateRandomString(80);
        $user->auth_key = Yii::$app->security->generateRandomString(80);

        return $user->save(runValidation: false) ? $user : null;
    }
}
