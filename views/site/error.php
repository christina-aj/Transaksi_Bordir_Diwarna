<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var $exception Exception */
/** @var $lastPage string */

$this->title = 'Error';

?>

<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Terjadi kesalahan saat memproses permintaan Anda.</p>

    <?php if (YII_DEBUG): ?>
        <p><strong>Error:</strong> <?= Html::encode($exception->getMessage()) ?></p>
    <?php endif; ?>

    <p>
        <?= Html::a('Kembali ke halaman sebelumnya', $lastPage, ['class' => 'btn btn-primary']) ?>
    </p>
</div>