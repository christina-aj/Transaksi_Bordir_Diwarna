<?php

use app\models\Penggunaan;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $model */
/** @var app\models\PenggunaanDetail[] $penggunaanDetails */

$this->title = 'Detail Penggunaan Kode: ' . $model->getFormattedGunaId();
$this->params['breadcrumbs'][] = ['label' => 'Penggunaans', 'url' => ['index']];
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
                <p><strong>Kode Penggunaan:</strong> <?= $model->getFormattedGunaId() ?></p>
                <p><strong>Nama Pengguna:</strong> <?= $model->user ? $model->user->nama_pengguna : '-' ?></p>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-4">
                <p><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($model->tanggal) ?></p>
                <p><strong>Total Item:</strong> <?= $model->total_item_penggunaan ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Status:</strong> <?= $model->getStatusLabel() ?></p>
                <?php if (!empty($model->permintaan_id)): ?>
                    <div><strong>Dari Permintaan:</strong> 
                        <?= Html::a(
                            $model->permintaanPelanggan->generateKodePermintaan(), 
                            ['permintaan-pelanggan/view', 'permintaan_id' => $model->permintaan_id],
                            ['class' => 'btn btn-sm btn-info']
                        ) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <br>
        <hr>


        <div class=" card-body mx-4">
            <div class="table-responsive">
                <!-- GridView for PesanDetail -->
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $penggunaanDetails,
                        'pagination' => false, // Sesuaikan jika tidak menggunakan pagination
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'header' => 'No'],
                        [
                            'attribute' => 'kode_barang',
                            'label' => 'Kode Barang',
                            'value' => function ($model) {
                                if ($model->barang) {
                                    return $model->barang->kode_barang;
                                }
                                return 'Barang tidak ditemukan';
                            },
                        ],
                        [
                            'attribute' => 'barang_id',
                            'label' => 'Nama Barang',
                            'value' => function ($model) {
                                if ($model->barang) {
                                    return $model->barang->nama_barang;
                                }
                                return 'Barang tidak ditemukan';
                            },
                        ],
                        [
                            'attribute' => 'jumlah_digunakan',
                            'label' => 'Quantity Penggunaan (KG)',
                        ],
                        // [
                        //     'attribute' => 'qty_terima',
                        //     'label' => 'Quantity Terima',
                        // ],
                        [
                            'attribute' => 'catatan',
                            'label' => 'Catatan',
                        ],
                        [
                            'attribute' => 'area_gudang',
                            'label' => 'Area Gudang',
                            'value' => function($model) {
                                if ($model->gudang && $model->gudang->area_gudang) {
                                    return 'Area ' . $model->gudang->area_gudang;
                                }
                                return $model->area_gudang ? 'Area ' . $model->area_gudang : '(not set)';
                            },
                        ],
                        // [
                        //     'attribute' => 'langsung_pakai',
                        //     'label' => 'Langsung Pakai',
                        //     'format' => 'raw',
                        //     'value' => function ($model) {
                        //         return $model->langsung_pakai == 1
                        //             ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                        //             : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                        //     },
                        // ],
                        // [
                        //     'attribute' => 'is_correct',
                        //     'label' => 'Barang Sesuai',
                        //     'format' => 'raw',
                        //     'value' => function ($model) {
                        //         return $model->is_correct == 1
                        //             ? Html::tag('span', '&#10004;', [
                        //                 'style' => 'color: green; font-size: 20px;',
                        //                 'class' => 'status-icon correct',  // Tambahkan class
                        //                 'data-id' => $model->pesandetail_id, // Tambahkan data-id
                        //                 'aria-disabled' => 'true', // Tambahkan atribut disabled menggunakan aria (tidak aktifkan pengguna)
                        //             ])
                        //             : Html::tag('span', '&#10008;', [
                        //                 'style' => 'color: red; font-size: 20px;',
                        //                 'class' => 'status-icon incorrect', // Tambahkan class berbeda untuk tidak sesuai
                        //                 'data-id' => $model->pesandetail_id, // Tambahkan data-id
                        //                 'aria-disabled' => 'true', // Tambahkan atribut disabled menggunakan aria
                        //             ]);
                        //     },
                        // ],
                    ],
                ]); ?>

                <div class="form-group mb-4">
                    <?php
                    $roleName = Yii::$app->user->identity->roleName; 
                    ?>
                    
                    <?php if ($model->status_penggunaan == 1): ?>
                        <!-- Jika status complete, semua role hanya bisa Back -->
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    
                    <?php elseif ($roleName == "Operator" && $model->status_penggunaan == 0): ?>
                        <!-- Operator hanya bisa edit jika status masih pending -->
                        <?= Html::a('Edit', ['update', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        
                    <?php elseif ($roleName == "Gudang" && $model->status_penggunaan == 0): ?>
                        <!-- Gudang hanya bisa update/complete jika status masih pending -->
                        <?= Html::a('Update', ['update-qty', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    
                    <?php elseif ($roleName == "Super Admin" && $model->status_penggunaan == 0): ?>
                        <!-- Gudang hanya bisa update/complete jika status masih pending -->
                        <?= Html::a('Edit', ['update', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Update', ['update-qty', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        
                    <?php else: ?>
                        <!-- Untuk semua role jika status complete, atau role lain -->
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$this->registerJs(
    <<<JS
    function checkAllCorrect() {
        let allCorrect = true;
        
        // Loop melalui setiap ikon status
        $('.status-icon').each(function() {
            if (!$(this).hasClass('correct')) {
                allCorrect = false;
                return false; // Keluar dari loop jika ditemukan ikon merah
            }
        });
        
        // Atur status tombol berdasarkan hasil pengecekan
        if (allCorrect) {
            $('#verify-button').removeClass('disabled-button').prop('disabled', false);
        } else {
            $('#verify-button').addClass('disabled-button').prop('disabled', true);
        }
    }

    // Cek status saat halaman selesai di-render
    $(document).ready(function() {
        checkAllCorrect();
    });
JS
);
?>

<style>
    .disabled-button {
        background-color: #d3d3d3;
        /* Warna abu-abu */
        color: #666;
        /* Warna teks lebih gelap */
        cursor: not-allowed;
        /* Tampilan kursor */
        pointer-events: none;
        /* Nonaktifkan klik */
    }
</style>