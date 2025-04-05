<?php

namespace App\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Provider;
use App\Models\ProviderPhoto;
use App\Models\Search\Provier;
use Yii;
use yii\db\Exception;
use yii\helpers\VarDumper;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProviderController implements the CRUD actions for Provider model.
 */
class ProviderController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Provider models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Provier();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Provider model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Provider model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new Provider();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $file = UploadedFile::getInstance($model, 'logo');

                if ($file) {
                    $fileName = uniqid() . '.' . $file->extension;
                    $filePath = Yii::getAlias('@webroot') . '/uploads/logos/' . $fileName;

                    if ($file->saveAs($filePath)) {
                        ImageHelper::resizeProportional(source: $filePath, maxWidth: 100, maxHeight: 100);
                        $model->logo = '/uploads/logos/' . $fileName;
                    }
                }

                $model->save();
                Yii::$app->session->setFlash('success', 'Banco salvo com sucesso!');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Provider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->canEdit()) {
            throw new ForbiddenHttpException('Você não tem permissão para editar este prestador.');
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $file = UploadedFile::getInstance($model, 'logo');

            if ($file) {
                $fileName = uniqid() . '.' . $file->extension;
                $filePath = Yii::getAlias('@webroot') . '/uploads/logos/' . $fileName;

                if ($file->saveAs($filePath)) {
                    ImageHelper::resizeProportional(source: $filePath, maxWidth: 100, maxHeight: 100);
                    $model->logo = '/uploads/logos/' . $fileName;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Banco salvo com sucesso!');
                return $this->redirect(['index']);
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Provider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->canEdit()) {
            throw new ForbiddenHttpException('Você não tem permissão para editar este prestador.');
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Provider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Provider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Provider::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGalleryUpload($id)
    {
        $provider = $this->findModel($id);
        $uploadForm = new ProviderPhoto();

        return $this->render('gallery-upload', [
            'provider' => $provider,
            'uploadForm' => $uploadForm,
        ]);
    }

}
