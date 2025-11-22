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

            <?php if ($model->status_permintaan === 3): ?>
                <div class="alert alert-secondary" role="alert" style="font-weight:bold;">
                    Data ini telah diarsipkan (Final). <br>
                    Anda tidak dapat mengubah atau menghapus data ini.
                </div>
            <?php endif; ?>
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
                    <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    
                    <?php if ($model->status_permintaan == 1): ?>
                        <!-- Tombol Mark as Complete untuk status On Progress -->
                        <?= Html::a('Complete', ['complete', 'permintaan_id' => $model->permintaan_id], [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => 'Apakah Anda yakin permintaan ini sudah selesai dan ingin menandai sebagai Complete?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <p style="color: rgba(192, 51, 51, 1);">
                            Note : Status On Progress. Sedang dikerjakan oleh tim produksi. Tekan complete apabila produksi telah selesai
                        </p>
                        
                    <?php elseif ($model->status_permintaan == 0 && $model->tipe_pelanggan == 1): ?>
                        <?= Html::a('Berikan Bahan Untuk Produksi', 
                            ['penggunaan/create', 'permintaan_id' => $model->permintaan_id], 
                            ['class' => 'btn btn-danger']
                        ) ?>
                        <p style="color: rgba(192, 51, 51, 1);">
                            Note : Status Pending. Bahan harus di pesan lalu langsung disalurkan ke tim produksi dan status otomatis berubah menjadi ON Progress.
                        </p>
                        
                    <?php elseif ($model->status_permintaan == 0 && $model->tipe_pelanggan == 2): ?>
                        <?= Html::a('Berikan Bahan Untuk Produksi', 
                            ['penggunaan/create', 'permintaan_id' => $model->permintaan_id], 
                            ['class' => 'btn btn-danger']
                        ) ?>
                        <p style="color: rgba(192, 51, 51, 1);">
                            Note : Status Pending. Bahan harus disalurkan ke tim produksi dan status otomatis berubah menjadi ON Progress.
                        </p>
                        
                    <?php elseif ($model->status_permintaan == 2): ?>
                        <!-- Status Complete -->
                        <p style="color: rgba(34, 139, 34, 1);">
                            Note : Status Complete. Produksi telah selesai dan siap dikirim/diambil pelanggan.
                        </p>
                        
                    <?php elseif ($model->status_permintaan == 3): ?>
                        <!-- Status Archived -->
                        <p style="color: rgba(128, 128, 128, 1);">
                            Note : Status Archived. Data telah difinalkan dan tidak dapat diubah.
                        </p>
                    <?php endif ?>
                </div>
                
            </div>
        </div>

    </div>
</div>
