<?php

/** @var \yii\web\View $this */
/** @var string $content */


use App\Assets\LoginAsset;
use yii\bootstrap5\Html;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <link href="/favicon.png" rel="icon">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="" name="description">

    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ? Html::encode($this->title) . ' - ' : '' ?>Prestadores de Serviços - Jardim dos Manacás</title>
    <?php $this->head() ?>

</head>
<body class='d-flex align-items-center py-4 bg-body-tertiary'>
<?php $this->beginBody() ?>
    <main class='form-signin w-300 m-auto'>
        <?php echo $content?>
    </main>
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
