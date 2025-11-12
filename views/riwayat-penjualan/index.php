<?php

use app\models\RiwayatPenjualan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

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
