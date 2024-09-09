<?php

use app\models\PembelianDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;

/** @var yii\web\View $this */
/** @var app\models\PembelianDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
if ($showFullContent) {
    $this->title = 'Buku Kas';
} else {
    $this->title = 'Surat Jalan';
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pembelian Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?php if ($showFullContent): ?>
    <?php endif; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_filter([
            ['class' => 'yii\grid\SerialColumn'],

            // 'belidetail_id',
            // 'pembelian_id',
            'pembelian.tanggal' =>
            [
                'attribute' => 'tanggal',
                'value' => 'pembelian.tanggal', // Menampilkan kolom tanggal
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


            'pembelian.kode_struk' => [
                'attribute' => 'kode_struk', // Atribut dari tabel supplier
                'value' => 'pembelian.kode_struk', // Mengakses nama supplier melalui relasi
                'label' => 'Kode Struk',
                'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                    'class' => 'form-control',       // Tambahkan class jika perlu
                    'placeholder' => 'Cari kode struk', // Placeholder yang ingin ditampilkan
                ],
            ],
            // 'barang_id',
            'barang.kode_barang' => [
                'attribute' => 'kode_barang', // Atribut dari tabel supplier
                'value' => 'barang.kode_barang', // Mengakses nama supplier melalui relasi
                'label' => 'Kode barang',
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

            $showFullContent ? [
                'attribute' => 'harga_barang', // Atribut dari tabel supplier
                'filter' => false,
            ] : null,

            'quantity_barang' => [
                'attribute' => 'quantity_barang', // Atribut dari tabel supplier
                'filter' => false,
            ],

            $showFullContent ? [
                'attribute' => 'total_biaya', // Atribut dari tabel supplier
                'filter' => false,
            ] : null,

            'langsung_pakai' => [
                'attribute' => 'langsung_pakai', // Atribut dari tabel supplier
                'filter' => false,
                'format' => 'raw', // This allows for raw HTML output (for icons)
                'value' => function ($model) {
                    // Check the value of the status field
                    if ($model->langsung_pakai == 1) {
                        // Active status (1)
                        return Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;']); // Checkmark icon
                    } else {
                        // Inactive status (0)
                        return Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']); // Cross icon
                    }
                },
            ],
            'catatan' => [
                'attribute' => 'catatan', // Atribut dari tabel supplier
                'filter' => false,
            ],
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PembelianDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'belidetail_id' => $model->belidetail_id]);
                }
            ],
        ]),
    ]); ?>


</div>