<?php

use app\models\Pemesanan;
use app\models\Gudang;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\pemesanan $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\PesanDetail[] $PemesananDetail */

/** @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="pembelian-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="row mx-3 ">
            <!-- Baris pertama: Kode Pembelian, Kode Pemesanan, Nama Pemesan -->
            <div class="col-md-4">
                <div><strong>Kode Pemesanan:</strong> <?= $model ? $model->getFormattedOrderId() : 'Kode Pemesanan Tidak Tersedia' ?></div>
                <div><strong>Nama Pemesan:</strong> <?= $model->user->nama_pengguna ?? 'Nama Pemesan Tidak Tersedia' ?></div>
            </div>
            <!-- Baris kedua: Tanggal Pemesanan, Total Item, Total Biaya, Aksi -->
            <div class="col-md-4">

                <div><strong>Tanggal Pemesanan:</strong> <?= Yii::$app->formatter->asDate($model->tanggal ?? 'Tanggal Tidak Tersedia') ?></div>
                <div><strong>Total Item:</strong> <?= $model->total_item ?? '-' ?></div>
            </div>
            <div class="col-md-4">
                <div><strong>Status:</strong> <?= $model->getStatusLabel() ?? '-' ?></div>
            </div>
        </div>
        <br>
        <hr>
        <div class="card-body mx-4">
            <?php $form = ActiveForm::begin(['action' => ['update-qty', 'pemesanan_id' => $model->pemesanan_id], 'method' => 'post']); ?>
            <?= GridView::widget([
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'allModels' => $modelDetails,
                    'pagination' => false,
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'kode_barang',
                        'label' => 'Kode Barang',
                        'value' => function ($model) {
                            return $model->barang ? $model->barang->kode_barang : 'Barang tidak ditemukan';
                        },
                    ],

                    [
                        'attribute' => 'barang_id',
                        'label' => 'Nama Barang',
                        'value' => function ($model) {
                            return $model->barang ? $model->barang->nama_barang : 'Barang tidak ditemukan';
                        },
                    ],

                    [
                        'attribute' => 'qty',
                        'label' => 'Qty',
                        'value' => 'qty',
                    ],

                    [
                        'attribute' => 'qty_terima',
                        'label' => 'Quantity Terima',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::textInput("PemesananDetail[{$model->pesandetail_id}][qty_terima]", $model->qty_terima, [
                                'class' => 'form-control cek-barang-input',
                                'data-id' => $model->pesandetail_id,
                            ]);
                        },
                    ],

                    [
                        'attribute' => 'area_gudang',
                        'label' => 'Diterima Di Gudang :',
                        'format' => 'raw',
                        'value' => function ($model) {
                            // Jika langsung_pakai = 1, area gudang tidak diperlukan
                            if ($model->langsung_pakai == 1) {
                                return Html::tag('span', 'Langsung Pakai', ['class' => 'badge badge-info']);
                            }
                            
                            // Dropdown untuk memilih area gudang (1-4)
                            return Html::dropDownList(
                                "PemesananDetail[{$model->pesandetail_id}][area_gudang]",
                                2, // default Area 2
                                Gudang::getAreaOptions(),
                                [
                                    'class' => 'form-control',
                                    'prompt' => 'Pilih Area',
                                    'data-id' => $model->pesandetail_id,
                                ]
                            );
                        },
                    ],

                    [
                        'attribute' => 'catatan',
                        'label' => 'Catatan',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::textInput("PemesananDetail[{$model->pesandetail_id}][catatan]", $model->catatan, [
                                'class' => 'form-control cek-barang-input',
                                'data-id' => $model->pesandetail_id,
                            ]);
                        },
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
                            return Html::checkbox("PemesananDetail[{$model->pesandetail_id}][is_correct]", $model->is_correct, [
                                'value' => 1,
                                'class' => 'form-check-input',
                            ]);
                        },
                    ],
                ],
            ]); ?>

            <div>
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back', ['view', 'pemesanan_id' => $model->pemesanan_id], ['class' => 'btn btn-secondary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>