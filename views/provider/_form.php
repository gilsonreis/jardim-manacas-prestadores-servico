<div class="provider-form">

    <?php use kartik\editors\Summernote;
    use yii\bootstrap4\ActiveForm;
    use yii\bootstrap4\Html;

    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'service_type_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\App\Models\ServiceType::find()->all(), 'id', 'name'),
                ['prompt' => 'Selecione o tipo do serviço']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'service_description')->widget(Summernote::class, [
                'pluginOptions' => [
                    'height' => 200,
                    'toolbar' => [
                        ['style', ['bold', 'italic', 'underline']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen', 'codeview']],
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'mobile_fone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'instagram')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'accept_email')->checkbox(['default' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'logo')->fileInput() ?>
        </div>
    </div>

    <div class='form-group btns-salvar'>
        <a href='/provider' class='btn btn-light float-left'>
            <svg class='icon me-2'>
                <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-arrow-thick-left'></use>
            </svg>
            Voltar
        </a>
        <button type='submit' class='btn btn-success float-right'>
            <svg class='icon me-2'>
                <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-check'></use>
            </svg>
            Salvar
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
