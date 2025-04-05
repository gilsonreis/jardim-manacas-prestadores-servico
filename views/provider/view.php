<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var App\Models\Provider $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class='card'>
    <div class='card-header d-flex justify-content-between'>
        <h4><?= $this->title ?></h4>
    </div>
    <div class='card-body'>

    <?php if($model->canEdit()):?>
    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'service_description:ntext',
            'logo:image',
            'serviceType.name',
            'phone',
            'accept_email:boolean',
            'mobile_fone',
            'contact_name',
            'contact_phone'
        ],
    ]) ?>
    </div>
</div>

<?php if (!empty($photos)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Galeria de Fotos</h5>
        </div>
        <div class="card-body">
            <div id="lightgallery" class="row g-3">
                <?php foreach ($photos as $photo): ?>
                    <div class="col-6 col-md-3">
                        <a href="<?= $photo->path ?>"
                           data-src="<?= $photo->path ?>"
                           data-lg-size="1000-1000"
                           data-sub-html="<?= $photo->description ?>">
                            <img src="<?= $photo->thumb ?>"
                                 class="img-fluid rounded shadow-sm"
                                 alt="<?= $photo->description ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lightGallery(document.getElementById('lightgallery'), {
                selector: 'a',
                plugins: [lgZoom, lgThumbnail],
                speed: 400,
                licenseKey: '0000-0000-000-0000'
            });
        });
    </script>
<?php endif; ?>
