<?php

use app\models\Penggunaan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use yii\bootstrap5\Alert;

/** @var yii\web\View $this */
/** @var app\models\PenggunaanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Penggunaan Bahan Produksi';
$this->params['breadcrumbs'][] = $this->title;

// Cek apakah ada flash message 'success'
if (Yii::$app->session->hasFlash('success')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success', // Kelas untuk menampilkan alert sukses
        ],
        'body' => Yii::$app->session->getFlash('success'),
    ]);
}

// Cek apakah ada flash message 'error'
if (Yii::$app->session->hasFlash('error')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger', // Kelas untuk menampilkan alert error
        ],
        'body' => Yii::$app->session->getFlash('error'),
    ]);
}
?>
<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Create Penggunaan', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'tanggal_digunakan',
                        'value' => 'tanggal_digunakan', // Menampilkan kolom tanggal
                        'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'tanggal_digunakan',
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
                    // 'barang_id',
                    'user.nama_pengguna' =>
                    [
                        'attribute' => 'nama_pengguna', // Atribut dari tabel supplier
                        'value' => 'user.nama_pengguna', // Mengakses nama supplier melalui relasi
                        'label' => 'Nama Pengguna',
                        'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                            'class' => 'form-control',       // Tambahkan class jika perlu
                            'placeholder' => 'Cari nama', // Placeholder yang ingin ditampilkan
                        ],

                    ],
                    'barang.kode_barang' => [
                        'attribute' => 'kode_barang', // Atribut dari tabel supplier
                        'value' => 'barang.kode_barang', // Mengakses nama supplier melalui relasi
                        'label' => 'kode Barang',
                        'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                            'class' => 'form-control',       // Tambahkan class jika perlu
                            'placeholder' => 'Cari kode barang', // Placeholder yang ingin ditampilkan
                        ],

                    ],
                    'barang.nama_barang' => [
                        'attribute' => 'nama_barang', // Atribut dari tabel supplier
                        'value' => 'barang.nama_barang', // Mengakses nama supplier melalui relasi
                        'label' => 'Nama Barang',
                        'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                            'class' => 'form-control',       // Tambahkan class jika perlu
                            'placeholder' => 'Cari Barang', // Placeholder yang ingin ditampilkan
                        ],

                    ],
                    'jumlah_digunakan' => [
                        'attribute' => 'jumlah_digunakan',
                        'filter' => false,
                    ],
                    'catatan' => [
                        'attribute' => 'catatan',
                        'filter' => false,
                    ],
                    // 'tanggal_digunakan',
                ],
            ]); ?>
        </div>
    </div>
</div>