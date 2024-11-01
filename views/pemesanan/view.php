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
                <?php
                // Tentukan warna berdasarkan status
                $statusColor = '';
                switch ($model->status) {
                    case Pemesanan::STATUS_PENDING:
                        $statusColor = 'color: orange;';
                        break;
                    case Pemesanan::STATUS_VERIFIED:
                        $statusColor = 'color: blue;';
                        break;
                    case Pemesanan::STATUS_COMPLETE:
                        $statusColor = 'color: green;';
                        break;
                }
                ?>
                <p><strong>Status:</strong><span style="<?= $statusColor ?>"> <?= $model->getStatusLabel() ?></span> </p>
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
                                    ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                                    : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
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
                        <?= Html::a('Update', ['index'], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    <?php else: ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>