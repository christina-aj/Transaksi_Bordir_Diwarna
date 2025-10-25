<?php

use app\models\PermintaanPelanggan;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPelanggan $model */
/** @var app\models\PermintaanDetail[] $permintaanDetails */

$this->title = 'Detail Permintaan Kode: ' . $model->generateKodePermintaan();
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Pelanggans', 'url' => ['index']];
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
                <p><strong>Kode Permintaan:</strong> <?= $model->generateKodePermintaan() ?></p>
                <p><strong>Nama Pelanggan:</strong> <?= $model->pelanggan ? $model->pelanggan->nama_pelanggan : '-' ?></p>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-4">
                <p><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($model->tanggal_permintaan) ?></p>
                <p><strong>Total Item:</strong> <?= $model->total_item_permintaan ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Status:</strong> <?= $model->getStatusLabel() ?></p>
            </div>
        </div>

        <div class="card-body mx-4">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $permintaanDetails,
                        'pagination' => false,
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        // Tampilkan Barang Custom jika tipe pelanggan = 1
                        [
                            'attribute' => 'barang_custom_pelanggan_id',
                            'label' => 'Barang Custom',
                            'visible' => $model->pelanggan && $model->tipe_pelanggan == 1,
                            'value' => function($model) {
                                return $model->barangCustomPelanggan ? $model->barangCustomPelanggan->nama_barang_custom : '-';
                            }
                        ],
                        
                        // Tampilkan Barang Produksi jika tipe pelanggan = 2
                        [
                            'attribute' => 'barang_produksi_id',
                            'label' => 'Barang Produksi',
                            'visible' => $model->pelanggan && $model->tipe_pelanggan == 2,
                            'value' => function($model) {
                                return $model->barangProduksi ? $model->barangProduksi->nama: '-';
                            }
                        ],

                        // 'permintaan_detail_id',
                        // 'permintaan_id',
                        // 'barang_produksi_id',
                        // 'barang_custom_pelanggan_id',
                        'qty_permintaan',
                    ],
                ]) ?>
                <div class="form-group mb-4">
                    <?php if ($model -> status_permintaan == 1): ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        <p style="color: rgba(192, 51, 51, 1);">Note : Status On Progress. Sedang dikerjakan oleh tim produksi. Tekan complete apabila produksi telah selesai </p>
                    <?php elseif ($model -> status_permintaan == 0 && $model -> tipe_pelanggan == 1): ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        <?= Html::a('Lakukan Pemesanan Untuk Permintaan', ['pemesanan/create'], ['class' => 'btn btn-danger']) ?>
                        <p style="color: rgba(192, 51, 51, 1);">Note : Status Pending. Bahan harus di pesan lalu langsung disalurkan ke tim produksi dan status otomatis berubah menjadi ON Progress. </p>
                    <?php elseif ($model -> status_permintaan == 0 && $model -> tipe_pelanggan == 2): ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        <?= Html::a('Berikan Bahan Untuk Produksi', 
                            ['penggunaan/create', 'permintaan_id' => $model->permintaan_id], 
                            ['class' => 'btn btn-danger']
                        ) ?>
                        <p style="color: rgba(192, 51, 51, 1);">Note : Status Pending. Bahan harus disalurkan ke tim produksi dan status otomatis berubah menjadi ON Progress. </p>
                    <?php else: ?>
                        <!-- Untuk semua role jika status complete, atau role lain -->
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    <?php endif ?>
                </div>
                
            </div>
        </div>

    </div>
</div>
