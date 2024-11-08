<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\LaporanProduksi $model */

$this->title = 'Update Laporan Produksi: ' . $model->laporan_id;
$this->params['breadcrumbs'][] = ['label' => 'Laporan Produksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->laporan_id, 'url' => ['view', 'laporan_id' => $model->laporan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
