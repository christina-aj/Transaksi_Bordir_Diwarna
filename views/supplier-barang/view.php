<?php

use app\models\SupplierBarang;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarang $model */
/** @var app\models\SupplierBarangDetail[] $supplierBarangDetails */

$this->title = 'Detail Supplier Barang : ' . $model->barang->nama_barang;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body mx-4">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $supplierBarangDetails,
                        'pagination' => false,
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn' , 'header' => 'No'],

                        // [
                        //     'label' => "Nama Barang",
                        //     'attribute' => 'barang_id',
                        //     'value' => function ($model) {
                        //         return $model->barang ? $model->barang->nama_barang : '-';
                        //     },
                        // ],
                        [
                            'label' => "Nama Supplier",
                            'attribute' => 'supplier_id',
                            'value' => function ($model) {
                                return $model->supplier->nama;
                            },
                        ],
                        [
                            'label' => 'Lead Time',
                            'attribute' => 'lead_time',
                            'value' => function ($model) {
                                return $model->lead_time . ' hari';
                            },
                        ],
                        [
                            'label' => 'Harga Barang',
                            'attribute' => 'harga_per_kg',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->harga_per_kg, 2, ',', '.') . ' / kg';
                            },
                        ],
                        [
                            'label' => 'Biaya Pesan',
                            'attribute' => 'biaya_pesan',
                            'value' => function ($model) {
                                return 'Rp ' . number_format($model->biaya_pesan, 2, ',', '.');
                            },
                        ],
                        'supp_utama:boolean',
                    ],
                ]) ?>

                <div class=form-group mb-4>
                    <p>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        <?= Html::a('Update', ['update', 'supplier_barang_id' => $model->supplier_barang_id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'supplier_barang_id' => $model->supplier_barang_id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                </div>
            </<div>
        </div>
    </div>
</div>
