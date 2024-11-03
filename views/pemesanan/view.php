<?php

use app\models\Pemesanan;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $model */
/** @var app\models\PesanDetail[] $pesanDetails */

$this->title = 'Detail Pemesanan Kode: ' . $model->getFormattedOrderId();
$this->params['breadcrumbs'][] = ['label' => 'Pemesanans', 'url' => ['index']];
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
                <p><strong>Kode Pemesanan:</strong> <?= $model->getFormattedOrderId() ?></p>
                <p><strong>Nama Pemesan:</strong> <?= $model->user ? $model->user->nama_pengguna : '-' ?></p>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-4">
                <p><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($model->tanggal) ?></p>
                <p><strong>Total Item:</strong> <?= $model->total_item ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Status:</strong> <?= $model->getStatusLabel() ?></p>
            </div>
        </div>
        <br>
        <hr>


        <div class=" card-body mx-4">
            <div class="table-responsive">
                <!-- GridView for PesanDetail -->
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $pesanDetails,
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
                            'attribute' => 'qty',
                            'label' => 'Quantity Pesan',
                        ],
                        [
                            'attribute' => 'qty_terima',
                            'label' => 'Quantity Terima',
                        ],
                        [
                            'attribute' => 'catatan',
                            'label' => 'Catatan',
                        ],
                        [
                            'attribute' => 'langsung_pakai',
                            'label' => 'Langsung Pakai',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->langsung_pakai == 1
                                    ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                                    : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                            },
                        ],
                        [
                            'attribute' => 'is_correct',
                            'label' => 'Barang Sesuai',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->is_correct == 1
                                    ? Html::tag('span', '&#10004;', [
                                        'style' => 'color: green; font-size: 20px;',
                                        'class' => 'status-icon correct',  // Tambahkan class
                                        'data-id' => $model->pesandetail_id, // Tambahkan data-id
                                        'aria-disabled' => 'true', // Tambahkan atribut disabled menggunakan aria (tidak aktifkan pengguna)
                                    ])
                                    : Html::tag('span', '&#10008;', [
                                        'style' => 'color: red; font-size: 20px;',
                                        'class' => 'status-icon incorrect', // Tambahkan class berbeda untuk tidak sesuai
                                        'data-id' => $model->pesandetail_id, // Tambahkan data-id
                                        'aria-disabled' => 'true', // Tambahkan atribut disabled menggunakan aria
                                    ]);
                            },
                        ],
                        // [
                        //     'attribute' => 'created_at',
                        //     'format' => 'datetime',
                        //     'label' => 'Dibuat Pada',
                        // ],
                        // [
                        //     'attribute' => 'update_at',
                        //     'format' => 'datetime',
                        //     'label' => 'Diperbarui Pada',
                        // ],
                    ],
                ]); ?>

                <div class="form-group mb-4">
                    <?php if ($model->status == 0): ?>
                        <?= Html::a('Edit', ['update', 'pemesanan_id' => $model->pemesanan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    <?php elseif ($model->status == 1): ?>
                        <?= Html::a('Update', ['update-qty', 'pemesanan_id' => $model->pemesanan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        <?= Html::a('Complete', ['verify', 'pemesanan_id' => $model->pemesanan_id], [
                            'class' => 'btn btn-warning',
                            'data-confirm' => 'Apakah Anda yakin ingin melakukan verifikasi?',
                            'data-method' => 'post',
                            'id' => 'verify-button', // Tambahkan ID untuk JavaScript
                        ])
                        ?>
                    <?php else: ?>
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