<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarangDetail $model */

$this->title = 'Update Supplier Barang Detail: ' . $model->supplier_barang_detail_id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Barang Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->supplier_barang_detail_id, 'url' => ['view', 'supplier_barang_detail_id' => $model->supplier_barang_detail_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supplier-barang-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
