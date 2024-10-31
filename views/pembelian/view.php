<?php

use app\models\Pemesanan;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Pembelian $model */
/** @var app\models\PembelianDetail[] $PembelianDetail */

$this->title = 'Detail Pembelian Kode : ' . $model->getFormattedBuyOrderId();
$this->params['breadcrumbs'][] = ['label' => 'Pembelians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="row mx-3 ">
            <!-- Baris pertama: Kode Pembelian, Kode Pemesanan, Nama Pemesan -->
            <div class="col-md-3">
                <div><strong>Kode Pembelian:</strong> <?= $model->getFormattedBuyOrderId() ?></div>
                <div><strong>Nama Pemesan:</strong> <?= $model->pemesanan->user->nama_pengguna ?? 'Nama Pemesan Tidak Tersedia' ?></div>
            </div>

            <!-- Baris kedua: Tanggal Pemesanan, Total Item, Total Biaya, Aksi -->
            <div class="col-md-3">
                <div><strong>Kode Pemesanan:</strong> <?= $model->pemesanan ? $model->pemesanan->getFormattedOrderId() : 'Kode Pemesanan Tidak Tersedia' ?></div>
                <div><strong>Tanggal Pemesanan:</strong> <?= Yii::$app->formatter->asDate($model->pemesanan->tanggal ?? 'Tanggal Tidak Tersedia') ?></div>

            </div>
            <div class="col-md-3">
                <div><strong>Total Item:</strong> <?= $model->pemesanan->total_item ?? '-' ?></div>
                <div><strong>Total Biaya:</strong> <?= Yii::$app->formatter->asCurrency($model->total_biaya) ?></div>
            </div>
            <div class="col-md-3">
                <?php
                // Tentukan warna berdasarkan status
                $statusColor = '';
                switch ($model->pemesanan->status) {
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
                <div><strong>Status:</strong> <span style="<?= $statusColor ?>"><?= $model->pemesanan->getStatusLabel() ?? '-' ?></span> </div>
            </div>
        </div>
        <br>
        <hr>

        <div class=" card-body mx-4">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $PembelianDetail,
                        'pagination' => false, // Sesuaikan jika tidak menggunakan pagination
                    ]),
                    // Harus menunjukkan 5 jika terdapat 5 data
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'header' => 'No'],
                        'pesanDetail.barang.kode_barang',
                        'pesanDetail.barang.nama_barang',
                        'pesanDetail.qty',
                        'pesanDetail.qty_terima',
                        'pesanDetail.catatan',
                        // 'pesanDetail.langsung_pakai' => [
                        //     'attribute' => 'pesanDetail.langsung_pakai',
                        //     'label' => 'Langsung Pakai',
                        //     'format' => 'raw',
                        //     'value' => function ($model) {
                        //         return $model->pesanDetail->langsung_pakai == 1
                        //             ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                        //             : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                        //     },
                        // ],
                        // 'pesanDetail.is_correct' => [
                        //     'attribute' => 'is_correct',
                        //     'label' => 'Barang Sesuai',
                        //     'format' => 'raw',
                        //     'value' => function ($model) {
                        //         return $model->pesanDetail->is_correct == 1
                        //             ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                        //             : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                        //     },
                        // ],
                        'cek_barang',
                        'is_correct'
                    ],

                ]);
                ?>

                <div class="form-group mb-4">
                    <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                </div>

            </div>
        </div>
    </div>
</div>