<?php

//use kartik\alert\AlertBlock;
use kartik\alert\AlertBlock;
use yii\helpers\Html;
use kartik\grid\GridView;
use App\Models\Provider;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var App\Models\Search\Provider $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Prestadores de Serviço';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='card'>
    <div class='card-header d-flex justify-content-between'>
        <h4><?= Html::encode($this->title) ?></h4>
        <?= Html::a('Novo Prestador', ['create'], ['class' => 'btn btn-success']) ?>
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
        <?php Pjax::begin(); ?>
        <?php echo $this->render('_search', ['model' => $searchModel]);

        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_provider_card',
            'layout' => "{items}\n{pager}",
            'options' => ['class' => 'row'],
            'itemOptions' => ['class' => 'col-md-12'],
        ]);

        Pjax::end(); ?>
    </div>
</div>
