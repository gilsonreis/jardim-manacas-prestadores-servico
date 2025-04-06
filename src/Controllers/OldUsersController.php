<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Search\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UsuariosController implements the CRUD actions for User model.
 */
class OldUsersController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ]
            ]
        );
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = "@app/views/layouts/modal";
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';

        if ($model->load($this->request->post())) {
            $model->setAttribute('password', Yii::$app->security->generatePasswordHash($model->password));
            $model->access_token = Yii::$app->security->generateRandomString(80);
            $model->auth_key = Yii::$app->security->generateRandomString(80);

            $model->repeatPassword = $model->password;

            if ($model->save()) {

                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole($model->role_name);
                $auth->assign($authorRole, $model->id);

                Yii::$app->session->setFlash("success", "Usuário salvo com sucesso!");
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldAuthorRoleString = $model->getAttribute('role_name');
        if ($this->request->isPost && $model->load($this->request->post())) {

            if (empty($model->password)) {
                $model->password = $model->currentPassword;
                $model->repeatPassword = $model->currentPassword;
            } else {
                $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $model->repeatPassword = $model->password;
            }

            if ($model->save()) {
                $auth = \Yii::$app->authManager;
                $oldAuthorRole = $auth->getRole($oldAuthorRoleString);
                $auth->revoke($oldAuthorRole, $id);

                $authorRole = $auth->getRole($model->role_name);
                $auth->assign($authorRole, $id);

                Yii::$app->session->setFlash("success", "Usuário alterado com sucesso!");
                return $this->redirect(['index']);
            } else {
                $model->currentPassword = "";
                $model->repeatPassword = "";
            }

        }

        unset($model->password);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     */
    public function actionDelete($id): Response
    {
        try {
            $usuario = User::findOne(['id' => $id, 'isDeleted' => false]);
            if ($usuario !== null) {
                $usuario->softDelete();
            }
        } catch (NotFoundHttpException $e) {
        }

        Yii::$app->session->setFlash("success", "Usuário excluído com sucesso!");
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): User
    {
        if (($model = User::findOne(['id' => $id, 'isDeleted' => false])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
