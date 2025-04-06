<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var App\Models\Search\Provider $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="provider-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class='col-md-12'>
            <h4>Filtrar por:</h4>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'service_type_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\App\Models\ServiceType::find()->all(), 'id', 'name'),
                ['prompt' => 'Selecione o tipo do serviço']
            ) ?>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end">
        <?= Html::resetButton('Limpar busca', ['class' => 'btn btn-outline-secondary mr-3']) ?>
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <hr>

</div>
