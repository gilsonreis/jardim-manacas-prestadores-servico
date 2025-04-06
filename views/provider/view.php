<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var App\Models\Provider $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Prestador', 'url' => ['index']];
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
            'service_description:html',
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
            <h5 class="mb-0">Imagens do Serviço</h5>
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
<?php endif; ?>

<div class="card my-4">
    <div class="card-header">
        <h5 class="mb-0">Comentários dos Moradores</h5>
    </div>
    <div class="card-body">
        <div id="comment-form" class="mb-4">
            <label for="comment-text" class="form-label">Deixe seu comentário:</label>
            <textarea id="comment-text" class="form-control mb-2" rows="3" placeholder="Escreva aqui..."></textarea>
            <button id="submit-comment" class="btn btn-primary">Enviar</button>
            <hr>
        </div>
        <div id="comments-container">
            <div class="text-muted">Carregando comentários...</div>
        </div>
    </div>
</div>

<?php
$commentsUrl = \yii\helpers\Url::to(['provider-comment/list', 'providerId' => $model->id]);
$commentPostUrl = \yii\helpers\Url::to(['provider-comment/create', 'providerId' => $model->id]);
$avatarFallback = 'https://ui-avatars.com/api/?name=Usuário&background=random';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const commentsUrl = '<?= $commentsUrl ?>';
        const commentPostUrl = '<?= $commentPostUrl ?>';
        const avatarFallback = '<?= $avatarFallback ?>';

        function loadComments() {
            fetch(commentsUrl)
                .then(response => response.json())
                .then(comments => {
                    const container = document.getElementById('comments-container');
                    container.innerHTML = '';

                    if (comments.length === 0) {
                        container.innerHTML = '<div class="text-muted">Nenhum comentário ainda.</div>';
                        return;
                    }

                    comments.forEach(comment => {
                        const photo = comment.photo || avatarFallback;
                        const commentEl = document.createElement('div');
                        commentEl.className = 'mb-4 pb-2 border-bottom';

                        const formattedComment = comment.comment.replace(/\n/g, '<br>');

                        commentEl.innerHTML = `
                            <div class="d-flex align-items-center mb-2">
                                <img src="${photo}" alt="Foto do usuário" class="rounded-circle me-2" width="40" height="40">
                                <div>
                                    <strong>${comment.name_user}</strong><br>
                                    <small class="text-muted">${comment.created_at}</small>
                                </div>
                            </div>
                            <div class="ms-1 ps-1">
                                <p class="mb-1">${formattedComment}</p>
                            </div>
                        `;

                        container.appendChild(commentEl);
                    });
                })
                .catch(() => {
                    document.getElementById('comments-container').innerHTML = '<div class="text-danger">Erro ao carregar comentários.</div>';
                });
        }

        loadComments();

        const submitBtn = document.getElementById('submit-comment');
        const textarea = document.getElementById('comment-text');

        submitBtn.addEventListener('click', function () {
            const comment = textarea.value.trim();
            if (!comment) return;

            fetch(commentPostUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ comment })
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    textarea.value = '';
                    loadComments();
                }
            });
        });

        lightGallery(document.getElementById('lightgallery'), {
            selector: 'a',
            plugins: [lgZoom, lgThumbnail],
            speed: 400,
            licenseKey: '0000-0000-000-0000'
        });
    });
</script>
