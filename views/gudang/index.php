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

    <p>
        <?= Html::a('Create Gudang', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            'quantity_akhiPr' => [
                'attribute' => 'quantity_akhir',
                'filter' => false,
            ],
            'area_gudang' => [
                'attribute' => 'area_gudang',
                'value' => function ($model) {
                    return $model->getAreaLabel();
                },
                'filter' => [1 => 'Area 1', 2 => 'Area 2', 3 => 'Area 3', 4 => 'Area 4'],
                'headerOptions' => ['style' => 'width:120px; text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'filterInputOptions' => [  
                    'class' => 'form-control',
                    'placeholder' => 'Cari Area',
                ],
            ],
            'catatan' => [
                'attribute' => 'catatan',
                'filter' => false,
            ],
            // 'created_at',
            // 'update_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete} {move}', // Tambah {move}
                'contentOptions' => ['style' => 'width: 120px; text-align: center;'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'Lihat Detail',
                            'class' => 'btn btn-xs btn-info mr-1',
                            'style' => 'padding: 4px 8px; margin: 1px;'
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => 'Edit',
                            'class' => 'btn btn-xs btn-primary mr-1',
                            'style' => 'padding: 4px 8px; margin: 1px;'
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Hapus',
                            'class' => 'btn btn-xs btn-danger mr-1',
                            'style' => 'padding: 4px 8px; margin: 1px;',
                            'data-confirm' => 'Yakin ingin menghapus?',
                            'data-method' => 'post',
                        ]);
                    },
                    'move' => function ($url, $model, $key) {
                        if ($model->quantity_akhir > 0) {
                            return Html::a('<i class="fas fa-exchange-alt"></i>', 
                                ['move-area', 'barang_id' => $model->barang_id, 'area_asal' => $model->area_gudang], 
                                [
                                    'title' => 'Pindah Area',
                                    'class' => 'btn btn-xs btn-warning mr-1',
                                    'style' => 'padding: 4px 8px; margin: 1px;'
                                ]
                            );
                        }
                        return '';
                    },
                ],
                'urlCreator' => function ($action, Gudang $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_gudang' => $model->id_gudang]);
                }
            ],
        ],
    ]); ?>


</div>