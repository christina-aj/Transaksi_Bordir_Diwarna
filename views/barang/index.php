<?php

use app\models\Barang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\BarangSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$pagination = $dataProvider->getPagination();

$this->title = 'List Barang';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">
    <div class="card table-card">
        <div class="card-header">
    <div class="card table-card">
        <div class="card-header">

            <!-- <h1><?= Html::encode($this->title) ?></h1> -->
            <h1>List Barang</h1>
        </div>
        <div class="card-body mx-4">
            <div class="table-responsive">
                <p>
                    <?= Html::a('Create Barang', ['create'], ['class' => 'btn btn-success']) ?>
                </p>

                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'barang_id',
                        // 'kode_barang',
                        [
                            'attribute' => 'kode_barang',
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari Kode Barang',
                            ],
                        ],
                        // 'nama_barang',
                        [
                            'attribute' => 'nama_barang',
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari Nama Barang',
                            ],
                        ],
                        'angka' => [
                            'attribute' => 'angka',
                            'filter' => false
                        ],
                        'unit.satuan' => [
                            'attribute' => 'satuan',
                            'value' => 'unit.satuan',
                            'label' => 'Satuan',
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari satuan',
                            ],
                        ],
                        'harga' =>
                        [
                            'attribute' => 'harga',
                            'filter' => false
                        ],
                        [
                            'attribute' => 'tipe',
                            'filter' => [
                                'Consumable' => 'Consumable',
                                'Non Consumable' => 'Non Consumable',
                            ],
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'prompt' => 'Pilih Tipe',
                            ],
                        ],

                        // 'tipe',
                        // 'warna',
                        // [
                        //     'attribute' => 'warna',
                        //     'filter' => false
                        // ],
                        'supplier.nama' => [
                            'attribute' => 'nama_supplier', // Atribut dari tabel supplier
                            'value' => 'supplier.nama', // Mengakses nama supplier melalui relasi
                            'label' => 'Nama Supplier',
                            'filterInputOptions' => [            // Menambahkan placeholder pada input filter
                                'class' => 'form-control',       // Tambahkan class jika perlu
                                'placeholder' => 'Cari Nama Supplier', // Placeholder yang ingin ditampilkan
                            ],
                        ],

                        //'created_at',
                        //'updated_at',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Barang $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'barang_id' => $model->barang_id]);
                            }
                        ],

                    ],
                    'pager' => [
                        'class' => LinkPager::class,
                        // Tidak perlu mengatur ulang pagination karena sudah didefinisikan di $dataProvider
                        'pagination' => $dataProvider->getPagination(),

                        // Mengatur label untuk halaman pertama dan terakhir
                        'firstPageLabel' => 'First', // Menampilkan 'First' jika bukan halaman pertama
                        'lastPageLabel' => 'Last', // Menampilkan 'Last' jika bukan halaman terakhir

                        // Label untuk halaman sebelumnya dan berikutnya
                        'nextPageLabel' => 'Next',
                        'prevPageLabel' => 'Previous', // Menampilkan 'Previous' jika bukan halaman pertama

                        // Opsi tampilan tambahan
                        'options' => ['class' => 'pagination'], // Kelas CSS untuk elemen pagination
                        'linkOptions' => ['class' => 'page-link'], // Kelas CSS untuk tautan
                        // 'disabledPageCssClass' => 'disabled', // Kelas untuk halaman yang dinonaktifkan
                        // 'activePageCssClass' => 'active', // Kelas untuk halaman yang aktif
                    ],
                ]); ?>


            </div>
        </div>
    </div>
</div>