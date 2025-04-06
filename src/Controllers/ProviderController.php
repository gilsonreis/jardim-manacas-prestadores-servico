<?php

namespace App\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Provider;
use App\Models\ProviderPhoto;
use App\Models\Search\ProviderSearch;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ProviderController implements the CRUD actions for Provider model.
 */
class ProviderController extends BaseController
{

    /**
     * Lists all Provider models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProviderSearch();
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
            'photos' => ProviderPhoto::find()
                ->where(['provider_id' => $id])
                ->all(),
        ]);
    }

    /**
     * Creates a new Provider model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new Provider();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $file = UploadedFile::getInstance($model, 'logo');

                if ($file) {
                    $fileName = uniqid() . '.' . $file->extension;
                    $filePath = Yii::getAlias('@webroot') . '/uploads/logos/';

                    FileHelper::createDirectory($filePath);
                    $filePath .= $fileName;

                    if ($file->saveAs($filePath)) {
                        ImageHelper::resizeProportional(source: $filePath, maxWidth: 100, maxHeight: 100);
                        $model->logo = '/uploads/logos/' . $fileName;
                    }
                }

                $model->save();
                Yii::$app->session->setFlash('success', 'Prestador salvo com sucesso!');
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
     * @return string|Response
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
                Yii::$app->session->setFlash('success', 'Prestador salvo com sucesso!');
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
     * @return Response
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

        $existingPhotos = ProviderPhoto::find()
            ->where(['provider_id' => $provider->id, 'user_id' => Yii::$app->user->id])
            ->all();

        return $this->render('gallery-upload', [
            'provider' => $provider,
            'uploadForm' => $uploadForm,
            'existingPhotos' => $existingPhotos,
        ]);
    }

    /**
     * @throws \yii\base\Exception
     * @throws NotFoundHttpException
     */
    public function actionGalleryUploadAjax($providerId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $files = UploadedFile::getInstancesByName('images');
        $results = [];

        $baseTmp = sys_get_temp_dir() . '/manaca_gallery';
        $sessionTmp = $baseTmp . '/' . Yii::$app->session->id;

        $originalDir = $sessionTmp . '/originals';
        $thumbDir = $sessionTmp . '/thumbs';

        FileHelper::createDirectory($originalDir);
        FileHelper::createDirectory($thumbDir);

        foreach ($files as $file) {
            $fileName = uniqid() . '.' . $file->extension;
            $originalPath = $originalDir . '/' . $fileName;

            if ($file->saveAs($originalPath)) {
                ImageHelper::resizeProportional($originalPath, 1000, 1000);

                $thumbPath = $thumbDir . '/' . $fileName;
                ImageHelper::resizeCrop($originalPath, $thumbPath, 150, 150);

                $results[] = [
                    'thumb' => $thumbPath,
                    'path' => $originalPath,
                    'filename' => $fileName,
                ];
            }
        }

        return $results;
    }

    /**
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionTmpImage($session, $type, $file)
    {
        $allowedTypes = ['originals', 'thumbs'];
        if (!in_array($type, $allowedTypes, true)) {
            throw new \yii\web\BadRequestHttpException('Tipo inválido.');
        }

        $baseTmp = sys_get_temp_dir() . '/manaca_gallery';
        $path = "$baseTmp/$session/$type/$file";

        if (!file_exists($path)) {
            $fallback = file_get_contents('https://ui-avatars.com/api/?name=Sem+Imagem&size=150');

            return Yii::$app->response->sendContentAsFile(
                $fallback,
                'fallback.png',
                ['inline' => true, 'mimeType' => 'image/png']
            );
        }

        return Yii::$app->response->sendFile($path, null, ['inline' => true]);
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionGallerySave($id)
    {
        $provider = $this->findModel($id);

        if (!Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Requisição inválida.');
        }

        $paths = Yii::$app->request->post('paths', []);
        $descriptions = Yii::$app->request->post('descriptions', []);

        if (count($paths) > 8) {
            throw new BadRequestHttpException('Você não pode enviar mais de 8 imagens.');
        }

        $session = Yii::$app->session->id;
        $baseTmp = sys_get_temp_dir() . '/manaca_gallery/' . $session;

        $finalDir = Yii::getAlias("@webroot") . "/uploads/gallery/{$provider->id}";
        $finalThumbDir = $finalDir . '/thumbs';
        FileHelper::createDirectory($finalDir);
        FileHelper::createDirectory($finalThumbDir);

        // Atualizar descrições das imagens já existentes
        $existingDescriptions = Yii::$app->request->post('descriptions_existing', []);
        foreach ($existingDescriptions as $photoId => $desc) {
            $photo = ProviderPhoto::find()
                ->where(['id' => $photoId, 'user_id' => Yii::$app->user->id])
                ->one();

            if ($photo) {
                $photo->description = $desc;
                $photo->save(false);
            }
        }

        foreach ($paths as $index => $tmpPath) {
            $basename = basename($tmpPath);
            $desc = $descriptions[$index] ?? '';

            $fromOriginal = "$baseTmp/originals/$basename";
            $fromThumb = "$baseTmp/thumbs/$basename";

            $webPath = "/uploads/gallery/{$provider->id}/$basename";
            $webThumb = "/uploads/gallery/{$provider->id}/thumbs/$basename";

            // Ignora se imagem já foi salva anteriormente
            if (ProviderPhoto::find()->where(['provider_id' => $provider->id, 'path' => $webPath])->exists()) {
                continue;
            }

            $toOriginal = "$finalDir/$basename";
            $toThumb = "$finalThumbDir/$basename";

            if (file_exists($fromOriginal)) {
                rename($fromOriginal, $toOriginal);
            }

            if (file_exists($fromThumb)) {
                rename($fromThumb, $toThumb);
            }

            $photo = new ProviderPhoto();
            $photo->provider_id = $provider->id;
            $photo->user_id = Yii::$app->user->id;
            $photo->description = $desc;
            $photo->path = $webPath;
            $photo->thumb = $webThumb;
            $photo->save();
        }

        Yii::$app->session->setFlash('success', 'Imagens salvas com sucesso!');
        return $this->redirect(['view', 'id' => $provider->id]);
    }

    public function actionDeletePhoto($id): Response
    {
        $photo = ProviderPhoto::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if (!$photo) {
            return $this->asJson(['success' => false, 'message' => 'Imagem não encontrada.']);
        }

        @unlink(Yii::getAlias('@webroot') . $photo->path);
        @unlink(Yii::getAlias('@webroot') . $photo->thumb);
        $photo->delete();

        return $this->asJson(['success' => true]);
    }

}
