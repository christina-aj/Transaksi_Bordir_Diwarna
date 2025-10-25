<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BarangCustomPelanggan $model */

$this->title = 'Create Barang Custom Pelanggan';
$this->params['breadcrumbs'][] = ['label' => 'Barang Custom Pelanggans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <?= $this->render('_form', [
        'pelanggan' => $pelanggan,
        'barangList' => $barangList,
        'unitList' => $unitList,
    ]) ?>

</div>
