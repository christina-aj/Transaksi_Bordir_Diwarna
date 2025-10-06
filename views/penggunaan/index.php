<?php

use app\models\Penggunaan;

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PenggunaanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Penggunaan Bahan Produksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Create Penggunaan', ['penggunaan/create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        // 'pemesanan_id' =>
                        [
                            'label' => 'Kode Penggunaan',
                            'attribute' => 'kode_penggunaan',
                            'value' => function ($model) {
                                return $model->getFormattedGunaId(); // Call the method to get the formatted ID
                            },
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari kode Penggunaan',
                            ],
                        ],
                        // 
                        [
                            'label' => 'Nama Pengguna',
                            'attribute' => 'nama_penggunaan',
                            'value' => 'user.nama_pengguna',
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari Nama pengguna',
                            ],
                        ],
                        [
                            'attribute' => 'tanggal',
                            'value' => 'tanggal', // Menampilkan kolom tanggal
                            'label' => 'Tanggal',
                            'filter' => DateRangePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'tanggal',
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

                        'total_item_penggunaan' => [
                            'label' => 'Total Item',
                            'attribute' => 'total_item_penggunaan',
                            'filter' => false
                        ],
                        'status_penggunaan' => [
                            'label' => 'Status Penggunaan',
                            'attribute' => 'status_penggunaan',
                            'value' => function ($model) {
                                return $model->getStatusLabel();
                            },
                            'format' => 'raw',
                            'filter' => false,
                        ],
                        // 'created_at',
                        //'updated_at',
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{view}',
                            'urlCreator' => function ($action, Penggunaan $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'penggunaan_id' => $model->penggunaan_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>