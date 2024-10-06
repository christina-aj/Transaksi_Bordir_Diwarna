<?php

use app\models\PesanDetail;
use kartik\typeahead\Typeahead;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PesanDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Detail Pemesanan Bahan Produksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pesan Detail', ['pesan-detail/create-pemesanan'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pesandetail_id',
            'kode_pemesanan' => [
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
            'barang.nama_barang' => [
                'label' => 'Nama barang',
                'attribute' => 'nama_barang',
                'value' => function ($model) {
                    $barang = $model->barang;
                    $unit = $barang->unit;
                    return $barang->kode_barang . ' - ' . $barang->nama_barang . ' - ' . $barang->angka . ' ' . ($unit ? $unit->satuan : 'Satuan tidak ditemukan') . ' - ' . $barang->warna;
                },
                'filter' => Typeahead::widget([
                    'name' => 'PesanDetailSearch[nama_barang]',
                    'pluginOptions' => ['highlight' => true],
                    'scrollable' => true,
                    'dataset' => [
                        [
                            'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                            'display' => 'value',
                            'templates' => [
                                'notFound' => "<div class='text-danger'>Tidak ada hasil</div>",
                                'suggestion' => new \yii\web\JsExpression('function(data) {
                                    return "<div>"  + data.kode_barang + " - " + data.nama_barang + " - " + data.angka + " " + data.satuan + " - " + data.warna + "</div>";
                                }'),
                            ],
                            'remote' =>
                            [
                                'url' => Url::to(['pesan-detail/search']) . '?q=%QUERY&is_search_form=true', // URL ke action yang digunakan untuk pencarian data
                                'wildcard' => '%QUERY',
                            ],
                        ],
                    ],
                    'options' => ['placeholder' => 'Cari barang...', 'class' => 'form-control'],
                ])
            ],

            'qty' => [
                'label' => 'Quantity Pesan',
                'attribute' => 'qty',
                'filter' => false,
            ],

            'qty_terima' => [
                'label' => 'Quantity Terima',
                'attribute' => 'qty_terima',
                'filter' => false,
            ],

            'catatan' => [
                'label' => 'Catatan',
                'attribute' => 'catatan',
                'filter' => false,
            ],

            'langsung_pakai' => [
                'label' => 'Langsung Pakai',
                'attribute' => 'langsung_pakai',
                'filter' => [
                    1 => 'Langsung Pakai',
                    0 => 'Tidak Langsung Pakai',
                ],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => 'Pilih Pemakaian',
                ],
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->langsung_pakai == 1
                        ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                        : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                },
            ],

            'is_correct' => [
                'label' => 'Barang Lengkap',
                'attribute' => 'is_correct',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->is_correct == 1
                        ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                        : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                },
            ],

            [
                'class' => ActionColumn::class,
                'template' => '{update}',
                'urlCreator' => function ($action, PesanDetail $modelDetail, $key, $index, $column) {
                    return Url::toRoute([$action, 'pesandetail_id' => $modelDetail->pesandetail_id]);
                }
            ],
        ],
    ]); ?>



</div>