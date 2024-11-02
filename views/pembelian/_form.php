<?php

use app\models\Pemesanan;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pembelian $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\PembelianDetail[] $PembelianDetail */

/** @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="pembelian-form">
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
                <div><strong>Status:</strong> <?= $model->pemesanan->getStatusLabel() ?? '-' ?></div>
            </div>
        </div>
        <br>
        <hr>
        <div class="card-body mx-4">
            <!-- <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10%;">Barang id</th>
                        <th style="width: 25%;">Nama Barang</th>
                        <th style="width: 5%;">Qty</th>
                        <th style="width: 30%;">Catatan</th>
                        <th style="width: 15%;">Harga</th>
                        <th style="width: 15%;">Total Biaya</th>
                        <th style="width: 5%;">Harga Sesuai</th>

                    </tr>
                </thead>

            </table> -->
            <?php $form = ActiveForm::begin(['action' => ['update', 'pembelian_id' => $model->pembelian_id], 'method' => 'post']); ?>
            <?= GridView::widget([
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'allModels' => $modelDetails,
                    'pagination' => false,
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'pesanDetail.barang.kode_barang',
                    'pesanDetail.barang.nama_barang',
                    'pesanDetail.qty' => [
                        'attribute' => 'qty',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return "<span class='qty-text' data-id='{$model->belidetail_id}'>{$model->pesanDetail->qty}</span>";
                        },
                    ],
                    'pesanDetail.catatan',
                    [
                        'attribute' => 'cek_barang',
                        'label' => 'Harga',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $column) {
                            return Html::textInput("PembelianDetail[{$model->belidetail_id}][cek_barang]", $model->cek_barang, [
                                'class' => 'form-control cek-barang-input', // Tambahkan kelas cek-barang-input
                                'data-id' => $model->belidetail_id,
                            ]);
                        },
                    ],
                    [
                        'attribute' => 'total_biaya',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $column) {
                            return Html::textInput("PembelianDetail[{$model->belidetail_id}][total_biaya]", $model->total_biaya, [
                                'class' => 'form-control',
                                'data-id' => $model->belidetail_id,
                                'readonly' => true, // Total biaya tidak bisa diedit langsung
                            ]);
                        },
                    ],
                    [
                        'attribute' => 'is_correct',
                        'label' => 'Harga Sesuai',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $column) {
                            return Html::checkbox("PembelianDetail[{$model->belidetail_id}][is_correct]", $model->is_correct, [
                                'value' => 1, // Nilai yang akan dikirim saat checkbox dicentang
                                'class' => 'form-check-input',
                            ]);
                        },
                    ],
                ],
            ]); ?>
            <div>
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back', ['view', 'pembelian_id' => $model->pembelian_id], ['class' => 'btn btn-secondary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
// JavaScript untuk menghitung total biaya secara dinamis
$this->registerJs(
    <<<JS
    function calculateTotal(id) {
        // Ambil nilai qty dari teks (bukan input)
        var qty = parseFloat($('[data-id="' + id + '"].qty-text').text()) || 0;
        var cekBarang = parseFloat($('[name="PembelianDetail[' + id + '][cek_barang]"]').val()) || 0;
        var totalBiaya = qty * cekBarang;
        $('[name="PembelianDetail[' + id + '][total_biaya]"]').val(totalBiaya.toFixed());
    }

    // Event untuk menghitung total biaya saat cek_barang berubah
    $('.cek-barang-input').on('keyup change', function() {
        var id = $(this).data('id');
        calculateTotal(id);
    });
JS
); ?>