<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \App\Models\Provider $model */

$logoUrl = $model->logo ?: 'https://ui-avatars.com/api/?name=' . urlencode($model->name) . '&size=100&background=0D8ABC&color=fff';
$viewUrl = Url::to(['provider/view', 'id' => $model->id]);
?>

<a href="<?= $viewUrl ?>" class="text-decoration-none text-dark">
    <div class="card mb-3 shadow-sm p-3" style="cursor: pointer;">
        <?php if ($model->canEdit()): ?>
            <div class="position-absolute top-0 end-0 p-2 d-flex gap-2">
                <?= Html::a('<i class="bi bi-pencil-square"></i>', ['provider/update', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-primary',
                    'title' => 'Editar',
                ]) ?>
                <?= Html::a('<i class="bi bi-trash"></i>', ['provider/delete', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-danger',
                    'title' => 'Excluir',
                    'data-confirm' => 'Tem certeza que deseja excluir este prestador?',
                    'data-method' => 'post',
                ]) ?>
                <?= Html::a('<i class="bi bi-image"></i> Adicionar imagens', ['provider/gallery-upload', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-secondary',
                    'title' => 'Adicionar fotos',
                ]) ?>
            </div>
        <?php endif; ?>
        <div class="row no-gutters align-items-center">
            <div class="col-md-3 text-center p-3">
                <?= Html::img($logoUrl, [
                    'alt' => 'Logo',
                    'class' => 'img-fluid rounded',
                    'style' => 'max-height:100px; object-fit:contain;'
                ]) ?>
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h5 class="card-title mb-1"><?php echo $model->name ?> - <small><?= Html::encode($model->serviceType->name) ?></small></h5>
                    <p class="card-text text-muted small mb-0 text-justify">
                        <?php echo nl2br($model->service_description ?? "") ?>
                    </p>

                    <?php if ($model->instagram || $model->website): ?>
                        <p class="mb-0 d-flex gap-2">
                            <?php if ($model->instagram): ?>
                                <a href="<?= Html::encode($model->instagram) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($model->website): ?>
                                <a href="<?= Html::encode($model->website) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Website">
                                    <i class="bi bi-globe"></i>
                                </a>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</a>