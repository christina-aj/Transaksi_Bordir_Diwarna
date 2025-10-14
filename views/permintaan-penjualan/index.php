<?php

use app\models\PermintaanPenjualan;

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPenjualanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Permintaan Penjualan';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Create Permintaan Penjualan', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'permintaan_penjualan_id',
                        [
                            'label' => 'Kode Permintaan',
                            'attribute' => 'kode_permintaan',
                            'value' => function ($model) {
                                return $model->getFormattedPermintaanId(); // Call the method to get the formatted ID
                            },
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari kode Permintaan',
                            ],
                        ],

                        [
                            'attribute' => 'tanggal_permintaan',
                            'value' => 'tanggal_permintaan', // Menampilkan kolom tanggal
                            'label' => 'Tanggal Permintaan',
                            'filter' => DateRangePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'tanggal_permintaan',
                                'convertFormat' => true,
                                'pluginOptions' => [
                                    'locale' => [
                                        'format' => 'd-m-Y',
                                        'separator' => ' - ',
                                    ],
                                    'autoUpdateInput' => false,
                                    'opens' => 'left',
                                ],
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Pilih rentang tanggal'
                                ]
                            ]),
                            'format' => ['date', 'php:d-M-Y'], // Format tampilan kolom tanggal
                            'headerOptions' => ['style' => 'width:250px'], // Tambahkan lebar jika diperlukan
                            'enableSorting' => true, // Mengaktifkan sorting untuk kolom tanggal
                        ],
                        [
                            'attribute' => 'nama_pelanggan',
                            'label' => 'Nama Pelanggan',
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari nama pelanggan',
                            ],
                        ],
                        'total_item_permintaan' => [
                            'label' => 'Total Item',
                            'attribute' => 'total_item_permintaan',
                            'filter' => false
                        ],
                        // 'created_at',
                        //'updated_at',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, PermintaanPenjualan $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'permintaan_penjualan_id' => $model->permintaan_penjualan_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
