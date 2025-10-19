<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarang $model */

$this->title = 'Update Supplier Barang: ' . $model->supplier_barang_id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->supplier_barang_id, 'url' => ['view', 'supplier_barang_id' => $model->supplier_barang_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <?= $this->render('_form', [
        'model' => $model,
        'supplierBarangDetails' => $supplierBarangDetails,
    ]) ?>

</div>
