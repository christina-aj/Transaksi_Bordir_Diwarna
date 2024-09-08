<?php

use app\models\Pembelian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;

/** @var yii\web\View $this */
/** @var app\models\PembelianSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pembelian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pembelian', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'pembelian_id',
            [
                'attribute' => 'kode_struk', // Atribut dari tabel supplier
                'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                    'class' => 'form-control',       // Tambahkan class jika perlu
                    'placeholder' => 'Cari Kode Struk', // Placeholder yang ingin ditampilkan
                ],
            ],
            [
                'attribute' => 'nama_pengguna', // Atribut dari tabel supplier
                'value' => 'user.nama_pengguna', // Mengakses nama supplier melalui relasi
                'label' => 'Nama Pengguna',
                'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                    'class' => 'form-control',       // Tambahkan class jika perlu
                    'placeholder' => 'Cari Nama', // Placeholder yang ingin ditampilkan
                ],
            ],
            'tanggal' =>
            [
                'attribute' => 'tanggal',
                'value' => 'tanggal', // Menampilkan kolom tanggal
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
            // 'tanggal',
            [
                'attribute' => 'nama_supplier', // Atribut dari tabel supplier
                'value' => 'supplier.nama', // Mengakses nama supplier melalui relasi
                'label' => 'Supplier',
                'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                    'class' => 'form-control',       // Tambahkan class jika perlu
                    'placeholder' => 'Cari Supplier', // Placeholder yang ingin ditampilkan
                ],

            ],
            [
                'attribute' => 'total_biaya', // Atribut dari tabel supplier
                'filter' => false,
            ],
            // 'total_biaya',
            [
                'attribute' => 'langsung_pakai', // Atribut dari tabel supplier
                'filter' => false,
            ],
            // 'langsung_pakai',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Pembelian $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pembelian_id' => $model->pembelian_id]);
                }
            ],
        ],
    ]); ?>


</div>