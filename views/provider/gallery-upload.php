<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \App\Models\Provider $provider */
/** @var \App\Models\UploadForm $uploadForm */
/** @var array $existingPhotos */

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

        <div class="d-flex align-items-center gap-3 mb-3">
            <button type="button" id="custom-file-trigger" class="btn btn-lg btn-primary">
                Selecionar imagens
            </button>
            <span id="image-count" class="text-muted">0 de 8 imagens selecionadas</span>
        </div>

        <?php if (!empty($existingPhotos)): ?>
            <div id="preview-grid" class="row gy-3 mt-4">
                <?php foreach ($existingPhotos as $photo): ?>
                    <div class="box-image-provider mb-4" data-photo-id="<?= $photo->id ?>">
                        <div class="row align-items-start p-3 border-1" style="background-color: #f1f1f1">
                            <div class="col-md-2">
                                <img src="<?= Html::encode($photo->thumb) ?>" class="img-thumbnail" style="width: 100%; height: auto;">
                            </div>
                            <div class="col-md-9">
                                <textarea name="descriptions_existing[<?= $photo->id ?>]" class="form-control mb-2" rows="3" placeholder="Descrição da imagem"><?= Html::encode($photo->description) ?></textarea>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-link text-danger btn-remove-existing" data-photo-id="<?= $photo->id ?>" title="Remover imagem">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <script>
                // Inicializa contador com base nas imagens já existentes
                document.addEventListener('DOMContentLoaded', function () {
                    const preview = document.getElementById('preview-grid');
                    const totalImages = preview ? preview.querySelectorAll('.box-image-provider').length : 0;
                    document.getElementById('image-count').textContent = `${totalImages} de 8 imagens selecionadas`;

                    // Evento para deletar imagens já existentes no banco
                    document.querySelectorAll('.btn-remove-existing').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const photoId = this.dataset.photoId;
                            if (!photoId || !confirm('Tem certeza que deseja remover esta imagem?')) return;

                            fetch(`<?= \yii\helpers\Url::to(['provider/delete-photo']) ?>?id=${photoId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(res => res.json())
                            .then(response => {
                                if (response.success) {
                                    const el = document.querySelector(`.box-image-provider[data-photo-id="${photoId}"]`);
                                    if (el) el.remove();

                                    const preview = document.getElementById('preview-grid');
                                    const totalImages = preview ? preview.querySelectorAll('.box-image-provider').length : 0;
                                    document.getElementById('image-count').textContent = `${totalImages} de 8 imagens selecionadas`;
                                } else {
                                    alert(response.message || 'Erro ao excluir imagem.');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('Erro ao excluir imagem.');
                            });
                        });
                    });
                });
            </script>
        <?php endif; ?>

        <?= $form->field($uploadForm, 'path[]')
            ->fileInput([
                'multiple' => true,
                'accept' => 'image/png, image/jpeg, image/jpg',
                'id' => 'providerphoto-path',
                'class' => 'd-none'
            ])
            ->label(false) ?>
        <hr>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('custom-file-trigger').addEventListener('click', function () {
            document.getElementById('providerphoto-path').click();
        });

        document.querySelector('#providerphoto-path').addEventListener('change', function(event) {
            const files = event.target.files;
            const preview = document.getElementById('preview-grid');
            const existingImages = preview ? preview.querySelectorAll('.box-image-provider').length : 0;
            const totalAfterUpload = existingImages + files.length;

            if (existingImages === 0 && files.length > 8) {
                alert('Você pode enviar no máximo 8 imagens.');
                event.target.value = '';
                return;
            }

            if (totalAfterUpload > 8) {
                const remaining = 8 - existingImages;
                alert(`Você já adicionou ${existingImages} imagens. Você pode enviar no máximo mais ${remaining}.`);
                event.target.value = '';
                return;
            }

            if (!files.length) return;

            const formData = new FormData();
            for (let i = 0; i < files.length && i < 8; i++) {
                formData.append('images[]', files[i]);
            }

            const csrfParam = document.querySelector('meta[name="csrf-param"]').getAttribute('content');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append(csrfParam, csrfToken);

            fetch('<?= \yii\helpers\Url::to(['provider/gallery-upload-ajax', 'providerId' => $provider->id]) ?>', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(images => {
                    let preview = document.getElementById('preview-grid');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'preview-grid';
                        preview.className = 'row gy-3 mt-4';
                        document.querySelector('form#w0').appendChild(preview);
                    }

                    const sessionId = '<?= Yii::$app->session->id ?>';

                    images.forEach((img, index) => {
                        const thumbUrl = `/provider/tmp-image?session=${sessionId}&type=thumbs&file=${img.filename}`;

                        const group = document.createElement('div');
                        group.className = 'box-image-provider';

                        group.innerHTML = `
                                <div class="row align-items-start p-3 border-1" style="background-color: #f1f1f1">
                                    <div class="col-md-2">
                                        <img src="${thumbUrl}" class="img-thumbnail" style="width: 100%; height: auto;">
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="descriptions[]" class="form-control mb-2" rows="3" placeholder="Descrição da imagem"></textarea>
                                        <input type="hidden" name="paths[]" value="${img.path}">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-link text-danger btn-remove-image" title="Remover imagem">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            `;

                        preview.appendChild(group);
                    });

                    // Atualiza o contador de imagens
                    const totalImages = preview.querySelectorAll('.box-image-provider').length;
                    document.getElementById('image-count').textContent = `${totalImages} de 8 imagens selecionadas`;

                    // Vincula eventos aos botões de exclusão
                    preview.querySelectorAll('.btn-remove-image').forEach(btn => {
                        btn.addEventListener('click', function () {
                            if (confirm('Tem certeza que deseja remover esta imagem?')) {
                                this.closest('.box-image-provider').remove();
                                const newCount = preview.querySelectorAll('.box-image-provider').length;
                                document.getElementById('image-count').textContent = `${newCount} de 8 imagens selecionadas`;
                            }
                        });
                    });

                    // Verifica se já existe botão, se não, cria
                    let btnWrapper = document.getElementById('btn-save-images-wrapper');
                    if (!btnWrapper) {
                        btnWrapper = document.createElement('div');
                        btnWrapper.id = 'btn-save-images-wrapper';
                        btnWrapper.className = 'form-group mt-4 text-end';

                        const btn = document.createElement('button');
                        btn.id = 'btn-save-images';
                        btn.type = 'submit';
                        btn.className = 'btn btn-success';
                        btn.textContent = 'Salvar imagens';

                        btnWrapper.appendChild(btn);

                        btn.addEventListener('click', function (e) {
                            e.preventDefault();

                            const form = document.querySelector('form');
                            const formData = new FormData(form);

                            fetch(form.action, {
                                method: 'POST',
                                body: formData
                            })
                            .then(res => res.ok ? window.location.href = '<?= \yii\helpers\Url::to(['provider/view', 'id' => $provider->id]) ?>' : Promise.reject(res))
                            .catch(err => {
                                console.error('Erro ao salvar imagens:', err);
                                alert('Ocorreu um erro ao salvar as imagens.');
                            });
                        });
                    }
                    document.querySelector('form').appendChild(btnWrapper);
                })
                .catch(err => {
                    console.error('Erro ao enviar imagens:', err);
                    alert('Falha no upload. Tente novamente.');
                });
        });
    });
</script>