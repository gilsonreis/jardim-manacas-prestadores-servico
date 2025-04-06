<?php


namespace App\Controllers;

use App\Models\LoginForm;
use app\models\User;
use Yii;
use yii\web\Response;

class AuthController extends \yii\web\Controller
{
    public function actionLogar()
    {
        return $this->render('logar');
    }

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

}
