<?php

use app\models\Gudang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;

/** @var yii\web\View $this */
/** @var app\models\GudangSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Stock Gudang';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id_gudang',
            'tanggal' =>
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
            'barang.kode_barang' => [
                'attribute' => 'kode_barang', // Atribut dari tabel supplier
                'value' => 'barang.kode_barang', // Mengakses nama supplier melalui relasi
                'label' => 'Kode Barang',
                'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                    'class' => 'form-control',       // Tambahkan class jika perlu
                    'placeholder' => 'Cari Barang', // Placeholder yang ingin ditampilkan
                ],
            ],
            // 'barang_id',
            'barang.nama_barang' => [
                'attribute' => 'nama_barang', // Atribut dari tabel supplier
                'value' => 'barang.nama_barang', // Mengakses nama supplier melalui relasi
                'label' => 'Nama Barang',
                'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                    'class' => 'form-control',       // Tambahkan class jika perlu
                    'placeholder' => 'Cari Barang', // Placeholder yang ingin ditampilkan
                ],

            ],
            // 'user_id',
            'user.nama_pengguna' =>
            [
                'attribute' => 'nama_pengguna', // Atribut dari tabel supplier
                'value' => 'user.nama_pengguna', // Mengakses nama supplier melalui relasi
                'label' => 'Nama Penerima',
                'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                    'class' => 'form-control',       // Tambahkan class jika perlu
                    'placeholder' => 'Cari Nama', // Placeholder yang ingin ditampilkan
                ],

            ],
            'quantity_awal' => [
                'attribute' => 'quantity_awal',
                'filter' => false,
            ],
            'quantity_masuk' => [
                'attribute' => 'quantity_masuk',
                'filter' => false,
            ],
            'quantity_keluar' => [
                'attribute' => 'quantity_keluar',
                'filter' => false,
            ],
            'quantity_akhir' => [
                'attribute' => 'quantity_akhir',
                'filter' => false,
            ],
            'catatan' => [
                'attribute' => 'catatan',
                'filter' => false,
            ],
            // 'created_at',
            // 'update_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Gudang $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_gudang' => $model->id_gudang]);
                }
            ],
        ],
    ]); ?>


</div>