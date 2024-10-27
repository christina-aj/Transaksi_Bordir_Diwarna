<?php

use app\models\Pemesanan;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PemesananSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pemesanan Bahan Produksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <p>
        <?= Html::a('Create Pesanan', ['pemesanan/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'pemesanan_id' =>
            [
                'label' => 'Kode Pemesanan',
                'attribute' => 'kode_pemesanan',
                'value' => function ($model) {
                    return $model->getFormattedOrderId(); // Call the method to get the formatted ID
                },
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Cari kode Pemesanan',
                ],
            ],
            // 
            [
                'label' => 'Nama Pemesan',
                'attribute' => 'nama_pemesan',
                'value' => 'user.nama_pengguna',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Cari Nama pemesan',
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

            'total_item' => [
                'label' => 'Total Item',
                'attribute' => 'total_item',
                'filter' => false
            ],
            // 'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function ($action, Pemesanan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pemesanan_id' => $model->pemesanan_id]);
                }
            ],
        ],
    ]); ?>


</div>