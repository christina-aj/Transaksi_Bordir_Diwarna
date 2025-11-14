<?php

use app\models\RiwayatPenjualan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RiwayatPenjualanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Riwayat Penjualan';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="pc-content">
    <div class="card card-table">

        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Create Riwayat Penjualan', ['riwayat-penjualan/create'], ['class' => 'btn btn-success']) ?>

            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['index'],
            ]); ?>

            <div class="row mt-4 mb-3">
                <div class="col-md-3">
                    <?php
                    $years = array_combine(range(date('Y'), 2023), range(date('Y'), 2023));
                    echo $form->field($searchModel, 'filter_tahun')->dropDownList(
                        $years,
                        ['prompt' => 'Pilih Tahun']
                    )->label(false);
                    ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($searchModel, 'filter_bulan')->dropDownList(
                        [
                            '1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April',
                            '5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus',
                            '9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
                        ],
                        ['prompt' => 'Pilih Bulan']
                    )->label(false) ?>
                </div>

                <div class="col-md-3">
                    <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Reset', ['index'], ['class' => 'btn btn-secondary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
        <div class="card-body">
            <div class="table-resposive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'riwayat_penjualan_id',
                        // [
                        //     'label' => 'Nama Barang',
                        //     'attribute' => 'nama',
                        //     'value' => 'barangProduksi.nama',
                        //     'filterInputOptions' => [
                        //         'class' => 'form-control',
                        //         'placeholder' => 'Cari Nama Barang',
                        //     ],
                        // ],
                        [
                            'attribute' => 'nama_barang',
                            'label' => 'Nama Barang',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->barangProduksi) {
                                    return Html::encode($model->barangProduksi->nama);
                                } elseif ($model->barangCustomPelanggan) {
                                    return Html::encode($model->barangCustomPelanggan->nama_barang_custom);
                                } else {
                                    return '<span class="text-muted">Tidak ada</span>';
                                }
                            },
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari Nama Barang',
                            ],
                        ],
                        // 'barang_produksi_id',
                        
                        'qty_penjualan' => [
                            'label' => 'QTY Barang Terjual',
                            'attribute' => 'qty_penjualan',
                            'filter' => false
                        ],

                        'bulan_periode' => [
                            'label' => 'Bulan Periode',
                            'attribute' => 'bulan_periode',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->getBulanPeriode();
                            },
                        ],
                        // 'created_at',
                        //'updated_at',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, RiwayatPenjualan $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'riwayat_penjualan_id' => $model->riwayat_penjualan_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
