<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarangDetail $model */

$this->title = $model->supplier_barang_detail_id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Barang Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="supplier-barang-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'supplier_barang_detail_id' => $model->supplier_barang_detail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'supplier_barang_detail_id' => $model->supplier_barang_detail_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'supplier_barang_detail_id',
            'supplier_barang_id',
            'supplier_id',
            'lead_time',
            'harga_per_kg',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
