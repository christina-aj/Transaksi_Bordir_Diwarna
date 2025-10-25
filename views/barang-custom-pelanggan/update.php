<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPelanggan $model */
/** @var app\models\BarangCustomPelanggan[] $existingProducts */
/** @var array $barangList */
/** @var array $unitList */

$this->title = 'Update Produk Custom : ' . $model->nama_pelanggan;
$this->params['breadcrumbs'][] = ['label' => 'Master Pelanggan', 'url' => ['master-pelanggan/index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_pelanggan, 'url' => ['master-pelanggan/view', 'pelanggan_id' => $model->pelanggan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <?= $this->render('_form_update', [
        'model' => $model,
        'existingProducts' => $existingProducts,
        'barangList' => $barangList,
        'unitList' => $unitList,
    ]) ?>

</div>