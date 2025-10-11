<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarang $model */

$this->title = $model->supplier_barang_id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="supplier-barang-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'supplier_barang_id' => $model->supplier_barang_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'supplier_barang_id' => $model->supplier_barang_id], [
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
            'supplier_barang_id',
            'barang_id',
            'supplier_id',
            'lead_time',
            'harga_per_kg',
            'created_at',
        ],
    ]) ?>

</div>
