<?php

use app\models\Barang;
use app\models\Gudang;
use yii\bootstrap5\Alert;
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

if (Yii::$app->session->hasFlash('success')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success', // Menggunakan styling success untuk pesan
        ],
        'body' => Yii::$app->session->getFlash('success'), // Menampilkan pesan flash
    ]);
}
?>
<div class="pc-content">
    <div class="card table-card">
        <div class="card-header">
            <!-- <h1><?= Html::encode($this->title) ?></h1> -->
            <h1>List Barang</h1>
            <?= Html::a('Create Barang', ['create'], ['class' => 'btn btn-success']) ?>
            <div style="margin-top: 12px;">
                <?php
                $jenis = Yii::$app->request->get('jenis', 'all');
                ?>
                <?= Html::a('Semua', ['index', 'jenis' => 'all'], [
                    'class' => $jenis == 'all' ? 'btn btn-primary' : 'btn btn-outline-primary',
                ]) ?>
                <?= Html::a('Barang Mentah', ['index', 'jenis' => Barang::KODE_BARANG_MENTAH], [
                    'class' => $jenis == Barang::KODE_BARANG_MENTAH ? 'btn btn-primary' : 'btn btn-outline-primary',
                ]) ?>
                <?= Html::a('Barang Set Jadi', ['index', 'jenis' => Barang::KODE_BARANG_SET_JADI], [
                    'class' => $jenis == Barang::KODE_BARANG_SET_JADI ? 'btn btn-primary' : 'btn btn-outline-primary',
                ]) ?>
                <?= Html::a('Alat dan Mesin', ['index', 'jenis' => Barang::KODE_BARANG_NON_CONSUM], [
                    'class' => $jenis == Barang::KODE_BARANG_NON_CONSUM ? 'btn btn-primary' : 'btn btn-outline-primary',
                ]) ?>
            </div>
        </div>
        <div class="card-body mx-4">
            <div class="table-responsive">


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
                        // 'angka' => [
                        //     'attribute' => 'angka',
                        //     'label' => 'Jumlah',
                        //     'filter' => false
                        // ],
                        // [
                        //     'attribute' => 'stock',
                        //     'label' => 'Stok',
                        //     'format' => 'html',
                        //     'value' => function ($model) {
                        //         $stockGudang = Gudang::getTotalStock($model->barang_id, Gudang::KODE_BARANG_GUDANG);
                        //         $stockProduksi = Gudang::getTotalStock($model->barang_id, Gudang::KODE_PENGGUNAAN);
                        //         $total = $stockGudang + $stockProduksi;
                                
                        //         return "
                        //             <div class='text-center'>
                        //                 <strong class='text-muted'>
                        //                     G : <strong>{$stockGudang}</strong> +  
                        //                     P : <strong>{$stockProduksi}</strong>
                        //                 </strong>
                        //                 <hr class='my-1'>
                        //                 <strong class='text-primary'>Total: {$total}</strong>
                        //             </div>
                        //         ";
                        //     },
                        //     'filter' => false,
                        //     'headerOptions' => [
                        //         'style' => 'width: 150px;',
                        //         'class' => 'text-primary'
                        //     ],
                        // ],
                        'unit.satuan' => [
                            'attribute' => 'satuan',
                            'value' => 'unit.satuan',
                            'label' => 'Satuan',
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cari satuan',
                            ],
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
                        [
                            'attribute' => 'jenis_barang',
                            'label' => 'Jenis Barang',
                            'value' => function ($model) {
                                return $model->getJenisBarangLabel();
                            },
                            'filter' => [
                                Barang::KODE_BARANG_MENTAH => 'Barang Mentah',
                                Barang::KODE_BARANG_SET_JADI => 'Barang Set Jadi',
                                Barang::KODE_BARANG_NON_CONSUM => 'Alat dan Mesin',
                            ],
                            'headerOptions' => [
                                'class' => 'text-primary'
                            ],
                        ],
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{update}',
                            'urlCreator' => function ($action, Barang $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'barang_id' => $model->barang_id]);
                            },
                        ],

                    ],
                ]); ?>


            </div>
        </div>
    </div>
</div>