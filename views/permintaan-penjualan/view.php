<?php

use yii\models\PermintaanPenjualan;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPenjualan $model */
/** @var app\models\DetailPermintaan[] $detailPermintaans */

$this->title = 'Detail Permintaan Kode: ' . $model->getFormattedPermintaanId();
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Penjualans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="pc-content">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="row mx-3">
            <!-- Kolom Kiri -->
            <div class="col-md-4">
                <p><strong>Kode Permintaan:</strong> <?= $model->getFormattedPermintaanId() ?></p>
                <p><strong>Total Item:</strong> <?= $model->total_item_permintaan ?></p>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-4">
                <p><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($model->tanggal_permintaan) ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Status:</strong> <?= $model->getStatusLabel() ?></p>
            </div>
        </div>
        <br>
        <hr>


        <div class=" card-body mx-4">
            <div class="table-responsive">

                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $detailPermintaans,
                        'pagination' => false, 
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'header' => 'No'],
                        [
                            'attribute' => 'barang_produksi_id',
                            'label' => 'Kode Barang Produksi',
                            'value' => function ($model) {
                                if ($model->barangProduksi) {
                                    return $model->barangProduksi->kode_barang_produksi;
                                }
                                return 'Barang Produksi tidak ditemukan';
                            },
                        ],
                        [
                            // 'attribute' => 'barang_produksi_id',
                            'label' => 'Nama Barang',
                            'value' => function ($model) {
                                if ($model->barangProduksi) {
                                    return $model->barangProduksi->nama;
                                }
                                return 'Barang Produksi tidak ditemukan';
                            },
                        ],
                        [
                            'attribute' => 'qty_permintaan',
                            'label' => 'Quantity Penggunaan',
                        ],
                    ],
                ]) ?>

                <div class=form-group mb-4>
                    <p>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        <?= Html::a('Update', ['update', 'permintaan_penjualan_id' => $model->permintaan_penjualan_id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'permintaan_penjualan_id' => $model->permintaan_penjualan_id], [
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
