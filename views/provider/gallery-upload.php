<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \App\Models\Provider $provider */
/** @var \App\Models\UploadForm $uploadForm */

$this->title = 'Enviar Fotos - ' . $provider->name;
$this->params['breadcrumbs'][] = ['label' => 'Prestadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $provider->name, 'url' => ['view', 'id' => $provider->id]];
$this->params['breadcrumbs'][] = 'Enviar Fotos';

?>

<div class='card'>
    <div class='card-header d-flex justify-content-between'>
        <h4><?= Html::encode($this->title) ?></h4>
    </div>
    <div class='card-body'>

        <p>Selecione até 8 imagens para enviar:</p>

        <?php $form = ActiveForm::begin([
            'action' => ['provider/gallery-save', 'id' => $provider->id],
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

        <?= $form->field($uploadForm, 'path[]')
            ->fileInput(['multiple' => true, 'accept' => 'image/*'])
            ->label('Imagens (máx. 8)') ?>

        <div class="form-group">
            <?= Html::submitButton('Enviar imagens', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>