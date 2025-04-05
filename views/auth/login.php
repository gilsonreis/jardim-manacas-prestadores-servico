<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \App\Models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['id' => 'loginform', 'class' => 'form-horizontal form-material']); ?>
<div class="d-flex justify-content-center">
<img class='mb-4 logo-login' src='/images/logo-manacas.png' alt='Logo Jardim dos Manacás' width='200'>
</div>
<h3 class="text-center m-b-20">Catálogo de Prestadores de Serviços</h3>
<h4 class="text-center m-b-20">Jardim dos Manacás</h4>

<div class="form-group ">
    <div class="col-xs-12">
        <?= $form->field($model, 'username', [
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
        ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]); ?>
    </div>
</div>
<div class="form-group">
    <?= $form->field($model, 'password', [
        'template' => '{beginWrapper}{input}{error}{endWrapper}'
    ])
        ->label(false)
        ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]); ?>
</div>
<div class="form-group text-center">
    <div class="col-xs-12 p-b-20">
        <?= Html::submitButton('ENTRAR', [
            'name' => 'login-button',
            'class' => 'btn w-100 btn-lg btn-success btn-rounded text-white'])
        ?>
    </div>
    <div class="d-flex justify-content-between">
        <div class="col-xs-12 p-b-20">
            <?= Html::a('Cadastrar-se', ['auth/cadastre-se'], ['class' => 'btn btn-link btn-link-login']) ?>
        </div>
        <div class="col-xs-12 p-b-20">
            <?= Html::a('Esqueci minha senha', ['auth/esqueci-minha-senha'], ['class' => 'btn btn-link btn-link-login']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
