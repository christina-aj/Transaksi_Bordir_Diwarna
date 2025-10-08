<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPenjualan $model */

$this->title = 'Update Permintaan Penjualan: ' . $model->permintaan_penjualan_id;
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Penjualans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->permintaan_penjualan_id, 'url' => ['view', 'permintaan_penjualan_id' => $model->permintaan_penjualan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permintaan-penjualan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
