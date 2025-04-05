<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var App\Models\Provider $model */

$this->title = 'Novo Prestador de Serviço';
$this->params['breadcrumbs'][] = ['label' => 'Prestador', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='card'>
    <div class='card-header d-flex justify-content-between'>
        <h4><?= Html::encode($this->title) ?></h4>
    </div>
    <div class='card-body'>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
