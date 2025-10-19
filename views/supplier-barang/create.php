<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarang $model */

$this->title = 'Create Supplier Barang';
$this->params['breadcrumbs'][] = ['label' => 'Supplier Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class=pc-content>

    <?= $this->render('_form', [
        'model' => $model,
        'supplierBarangDetails' => $supplierBarangDetails,
    ]) ?>

</div>
