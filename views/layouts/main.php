<?php

/** @var yii\web\View $this */
/** @var string $content */

use App\Assets\AppAsset;
use kartik\icons\FontAwesomeAsset;

AppAsset::register($this);
FontAwesomeAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang='<?= Yii::$app->language ?>' data-coreui-theme='light'>
<head>
    <title><?php echo $this->title ? $this->title . " | " : "" ?> Jardim dos Manacás - Sistema de Utilidades </title>
    <?php $this->head() ?>
    <script>
        window.localStorage.setItem('coreui-free-bootstrap-admin-template-theme', 'light');
    </script>
</head>
<body>
<?php $this->beginBody() ?>
<div class='sidebar sidebar-light sidebar-fixed border-end' id='sidebar'>
    <div class='sidebar-header border-bottom'>
        <div class='sidebar-brand'>
            <img src="/images/logo-manacas.png" width="100%" alt="">
        </div>
        <button class='btn-close d-lg-none' type='button' data-coreui-theme='dark' aria-label='Close'
                onclick='coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()'></button>
    </div>
    <ul class='sidebar-nav' data-coreui='navigation' data-simplebar=''>
        <li class='nav-item'><a class='nav-link' href='/dashboard'>
                <svg class='nav-icon'>
                    <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-speedometer'></use>
                </svg>
                Dashboard
            </a>
        </li>
        <li class='nav-item'><a class='nav-link' href='/provider'>
                <svg class='nav-icon'>
                    <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-people'></use>
                </svg>
                Prestador de Serviço
            </a>
        </li>
        <?php if(Yii::$app->user?->identity?->is_admin === 1):?>
        <li class='nav-item'>
            <a class='nav-link' href='/fix-request'>
                <svg class='nav-icon'>
                    <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-bug'></use>
                </svg>
                Solicitação de Reparos
            </a>
        </li>

        <li class='nav-item'>
            <a class='nav-link' href='/users'>
                <svg class='nav-icon'>
                    <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-user'></use>
                </svg>
                Usuários
            </a>
        </li>
        <?php endif;?>
        <li class='nav-item'>
            <hr>
            <a class='nav-link' href='/profile'>
                <svg class='nav-icon'>
                    <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-mood-good'></use>
                </svg>
                Meu Perfil
            </a>
        </li>
        <li class='nav-item'>
            <hr>
            <a class='nav-link text-danger' href='/auth/logout'>
                <svg class='nav-icon'>
                    <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-account-logout'></use>
                </svg>
                Sair
            </a>
        </li>
    </ul>
    <div class='sidebar-footer border-top d-none d-md-flex'>
        <button class='sidebar-toggler' type='button' data-coreui-toggle='unfoldable'></button>
    </div>
</div>
<div class='wrapper d-flex flex-column min-vh-100'>
    <header class='header header-sticky p-0 mb-4'>
        <div class='container-fluid border-bottom px-4'>
            <button class='header-toggler' type='button'
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
                    style='margin-inline-start: -14px;'>
                <svg class='icon icon-lg'>
                    <use xlink:href='/vendors/@coreui/icons/svg/free.svg#cil-menu'></use>
                </svg>
            </button>
            <ul class='header-nav'>
                <li class='nav-item py-1 text-right'>
                    Bem vindo <br><strong><?php echo Yii::$app->user->identity->name ?></strong>
                </li>
            </ul>
        </div>
        <div class='container-fluid px-4'>
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <nav aria-label="breadcrumb">
                    <?= \yii\widgets\Breadcrumbs::widget([
                        'options' => ['class' => 'breadcrumb my-0'], // <ol class="breadcrumb my-0">
                        'tag' => 'ol',
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n", // padrão
                        'activeItemTemplate' => "<li class='breadcrumb-item active' aria-current='page'><span>{link}</span></li>\n",
                        'links' => $this->params['breadcrumbs'],
                    ]) ?>
                </nav>
            <?php endif; ?>
        </div>
    </header>
    <div class='body flex-grow-1'>
        <div class='container-lg px-4'>
            <?= $content ?>
        </div>
    </div>
    <footer class='footer px-4'>
        <div class='ms-auto'>Desenvolvido by&nbsp;<a href='https://simplifysoftwares.com.br'>Simplify Softwares</a></div>
    </footer>
</div>

<script>
    const header = document.querySelector('header.header');

    document.addEventListener('scroll', () => {
        if (header) {
            header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
        }
    });
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


