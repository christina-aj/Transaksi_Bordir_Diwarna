<?php

use app\models\Stock;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use rmrevin\yii\fontawesome\FA;

/** @var yii\web\View $this */
/** @var app\models\StockSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>

        <div class="card-body mx-4">
            <div class="table-responsive">
                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'class' => 'card-body',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'stock_id',
                        [
                            'attribute' => 'tambah_stock',
                            'value' => 'tambah_stock', // Menampilkan kolom tanggal
                            'label' => 'Tanggal',
                            'filter' => DateRangePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'tambah_stock',
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
                        // 'tambah_stock',
                        // 'barang_id',
                        'barang.kode_barang' => [
                            'attribute' => 'kode_barang',
                            'value' => 'barang.kode_barang',
                            'label' => 'Kode barang',
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari kode barang',
                            ],
                        ],
                        'barang.nama_barang' => [
                            'attribute' => 'nama_barang',
                            'value' => 'barang.nama_barang',
                            'label' => 'Nama Barang',
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari Barang',
                            ],
                        ],
                        'quantity_awal' => [
                            'attribute' => 'quantity_awal', // Atribut dari tabel supplier
                            'filter' => false,
                        ],
                        'quantity_masuk' => [
                            'attribute' => 'quantity_masuk', // Atribut dari tabel supplier
                            'filter' => false,
                        ],
                        'quantity_keluar' => [
                            'attribute' => 'quantity_keluar', // Atribut dari tabel supplier
                            'filter' => false,
                        ],
                        'quantity_akhir' => [
                            'attribute' => 'quantity_akhir', // Atribut dari tabel supplier
                            'filter' => false,
                        ],
                        // 'user_id',
                        'user.nama_pengguna' => [
                            'attribute' => 'nama_pengguna', // Atribut dari tabel supplier
                            'value' => 'user.nama_pengguna', // Mengakses nama supplier melalui relasi
                            'label' => 'Nama Pengguna',
                            'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                                'class' => 'form-control',       // Tambahkan class jika perlu
                                'placeholder' => 'Cari Nama', // Placeholder yang ingin ditampilkan
                            ],
                        ],
                        'is_ready' => [
                            'attribute' => 'is_ready', // Atribut dari tabel supplier
                            'filter' => false,
                            'format' => 'raw', // This allows for raw HTML output (for icons)
                            'value' => function ($model) {
                                // Check the value of the status field
                                if ($model->is_ready == 1) {
                                    // Active status (1)
                                    return Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;']); // Checkmark icon
                                } else {
                                    // Inactive status (0)
                                    return Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']); // Cross icon
                                }
                            },
                        ],
                        'is_new' => [
                            'attribute' => 'is_new', // Atribut dari tabel supplier
                            'filter' => false,
                            'format' => 'raw', // This allows for raw HTML output (for icons)
                            'value' => function ($model) {
                                // Check the value of the status field
                                if ($model->is_new == 1) {
                                    // Active status (1)
                                    return Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;']); // Checkmark icon
                                } else {
                                    // Inactive status (0)
                                    return Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']); // Cross icon
                                }
                            },
                        ],
                        // 'created_at',
                        // 'updated_at',
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{view}',
                            'urlCreator' => function ($action, Stock $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'stock_id' => $model->stock_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>