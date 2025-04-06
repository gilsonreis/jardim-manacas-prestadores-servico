<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \App\Models\Forms\RegisterForm $model */

use kartik\alert\AlertBlock;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Cadastro';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id' => 'register-form', 'class' => 'form-horizontal form-material']); ?>

<div class="d-flex justify-content-center">
    <img class='mb-4 logo-login' src='/images/logo-manacas.png' alt='Logo Jardim dos Manacás' width='200'>
</div>

<h3 class="text-center m-b-20">Catálogo de Prestadores de Serviços</h3>
<h4 class="text-center m-b-20">Jardim dos Manacás</h4>

<div class='row'>
    <div class='col-md-12 mt-2'>
        <?php
        echo AlertBlock::widget([
            'useSessionFlash' => true,
            'type' => AlertBlock::TYPE_ALERT,
            'delay' => 5000
        ]);
        ?>
    </div>
</div>

<div class="form-group">
    <?= $form->field($model, 'name', [
        'template' => '{beginWrapper}{input}{error}{endWrapper}',
    ])->label(false)->textInput(['placeholder' => 'Nome completo']) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'email', [
        'template' => '{beginWrapper}{input}{error}{endWrapper}',
    ])->label(false)->textInput(['placeholder' => 'E-mail']) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'password', [
        'template' => '{beginWrapper}{input}{error}{endWrapper}',
    ])->label(false)->passwordInput(['placeholder' => 'Senha']) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'repeatPassword', [
        'template' => '{beginWrapper}{input}{error}{endWrapper}',
    ])->label(false)->passwordInput(['placeholder' => 'Confirme a senha']) ?>
</div>

<div class="form-group">
    <?= $form->field($model, 'accept_email', [
        'template' => '{beginWrapper}<div class="form-check">{input} {label}</div>{error}{endWrapper}',
    ])->checkbox(['label' => 'Quero receber emails sobre novos prestadores']) ?>
</div>

<div class="form-group text-center">
    <div class="col-xs-12 p-b-20">
        <?= Html::submitButton('Cadastrar', [
            'class' => 'btn w-100 btn-lg btn-success btn-rounded text-white',
            'name' => 'register-button'
        ]) ?>
    </div>

    <div class="text-center">
        <?= Html::a('Já tenho uma conta', ['auth/login'], ['class' => 'btn btn-link btn-link-login']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
