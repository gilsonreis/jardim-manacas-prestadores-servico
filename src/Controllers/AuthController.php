<?php


namespace App\Controllers;

use App\Models\Forms\LoginForm;
use App\Models\Forms\RegisterForm;
use app\models\User;
use Yii;
use yii\web\Response;

class AuthController extends \yii\web\Controller
{


    public function actionLogin()
    {
        $this->layout = "@app/views/layouts/login";
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/dashboard');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = User::findIdentity($model->getUser()->id);
            $user->save(false);

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $this->layout = '@app/views/layouts/login';
        $form = new RegisterForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            if ($form->register()) {
                Yii::$app->session->setFlash('success',  "Usuário registrado com sucesso! <br>Você já pode fazer login.");
                return $this->goHome();
            }
        }

        return $this->render('register', [
            'model' => $form,
        ]);
    }

}
