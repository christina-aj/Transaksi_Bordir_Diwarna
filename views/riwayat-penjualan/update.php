<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\RiwayatPenjualan[] $models */

$this->title = 'Update Riwayat Penjualan: ' . $models[0]->riwayat_penjualan_id;
$this->params['breadcrumbs'][] = ['label' => 'Riwayat Penjualans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $models[0]->riwayat_penjualan_id, 'url' => ['view', 'riwayat_penjualan_id' => $models[0]->riwayat_penjualan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">
    
    <?= $this->render('_form', [
        'models' => $models,  // ← Pass ke _form yang sama
    ]) ?>

</div>