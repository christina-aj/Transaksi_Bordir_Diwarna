<?php

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
        <div class="card-body mx-4">
            <div class="table-responsive">
                <!-- DetailView for Pemesanan -->
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'kode_pemesanan' => [
                            'label' => 'Kode Pemesanan',
                            'atttribute' => 'kode_pemesanan',
                            'value' => function ($model) {
                                return $model->getFormattedOrderId(); // Call the method to get the formatted ID
                            },
                        ],
                        // 'user_id',
                        'user.nama_pengguna',
                        [
                            'attribute' => 'tanggal',
                            'format' => ['date', 'php:d-M-Y'], // Format tanggal menjadi dd-MMM-yyyy
                            'label' => 'Tanggal Pemesanan',
                        ],
                        [
                            'attribute' => 'total_item',
                            'label' => 'Total Item',
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => 'datetime',
                            'label' => 'Dibuat Pada',
                        ],
                        [
                            'attribute' => 'updated_at',
                            'format' => 'datetime',
                            'label' => 'Diperbarui Pada',
                        ],
                    ],
                ]) ?>
                <hr>

                <!-- GridView for PesanDetail -->
                <!-- DetailView for each PesanDetail -->
                <?php foreach ($pesanDetails as $index => $detail): ?>
                    <h3>Item #<?= $index + 1 ?></h3>
                    <?= DetailView::widget([
                        'model' => $detail,
                        'attributes' => [
                            [
                                'attribute' => 'barang_id',
                                'label' => 'Nama Barang',
                                'value' => function ($model) {
                                    if ($model->barang) {
                                        return $model->barang->kode_barang . ' - ' . $model->barang->nama_barang;
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
                                        ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                                        : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                                },
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                                'label' => 'Dibuat Pada',
                            ],
                            [
                                'attribute' => 'update_at',
                                'format' => 'datetime',
                                'label' => 'Diperbarui Pada',
                            ],
                        ],
                    ]) ?>
                    <hr>
                <?php endforeach; ?>
                <div class="form-group mb-4">
                    <?= Html::a('Tambah Pesan Detail', ['pesan-detail/create', 'pemesanan_id' => $model->pemesanan_id], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Update Item', ['pesan-detail/update-multiple', 'pemesanan_id' => $detail->pemesanan_id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Delete Item', ['pesan-detail/delete', 'pesandetail_id' => $detail->pesandetail_id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Apakah Anda yakin ingin menghapus item ini?',
                            'method' => 'post',
                        ],
                    ]) ?>
                    <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>