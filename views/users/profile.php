<?php

use kartik\alert\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var App\Models\User $model */

$this->title = 'Meus Dados';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='card'>
    <div class='card-header d-flex justify-content-between'>
        <h4><?= Html::encode($this->title) ?></h4>
    </div>
    <div class='card-body'>
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
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email')->input('email') ?>
<?= $form->field($model, 'password')->passwordInput()->label('Senha') ?>
<?= $form->field($model, 'repeatPassword')->passwordInput()->label('Confirme a senha') ?>
<?= $form->field($model, 'accept_email')->checkbox() ?>

<div class="row align-items-center">
    <div class="col-md-3">
        <?php if (!empty($model->photoName) && file_exists(Yii::getAlias('@webroot/') . $model->photoName)) : ?>
            <img src="<?php echo $model->photoName ?>" class="img-thumbnail mb-3" width="150" alt="">
        <?php else : ?>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($model->name) ?>&size=150" class="img-thumbnail mb-3" alt="">
        <?php endif; ?>
    </div>
    <div class="col-md-9">
        <?= $form->field($model, 'photo')->fileInput() ?>
    </div>
</div>

<div class="form-group mt-3">
    <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

    </div>
</div>
