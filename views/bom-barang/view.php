<?php

use app\models\BomBarang;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\BomBarang $model */
/** @var app\models\BomDetail[] $bomDetails */

$this->title = 'Detail BOM ' . $model->barangProduksi->nama;
$this->params['breadcrumbs'][] = ['label' => 'Bom Barangs', 'url' => ['index']];
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
                        'allModels' => $bomDetails,
                        'pagination' => false, // Sesuaikan jika tidak menggunakan pagination
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn' , 'header' => 'No'],

                        // [
                        //     'label' => "Nama Barang Produksi",
                        //     'attribute' => 'barang_produksi_id',
                        //     'value' => function ($model) {
                        //         return $model->barangProduksi->nama;
                        //     },
                        // ],
                        [
                            'label' => "Nama Bahan Baku",
                            'attribute' => 'barang_id',
                            'value' => function ($model) {
                                return $model->barang ? $model->barang->nama_barang : '-';
                            },
                        ],
                        'qty_BOM',
                        'catatan',
                    ],
                ]); ?>

                <div class=form-group mb-4>
                    <p>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        <?= Html::a('Update', ['update', 'BOM_barang_id' => $model->BOM_barang_id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'BOM_barang_id' => $model->BOM_barang_id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
