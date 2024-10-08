<?php

use app\models\PembelianDetail;
use app\models\Barang;
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

$this->title = 'Pembelian Detail';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($showFullContent)
            echo Html::a('Create Pembelian Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_filter([
            ['class' => 'yii\grid\SerialColumn'],

            'belidetail_id',
            'pembelian_id',
            'pembelian.pemesanan_id',
            'pesanDetail.barang.nama_barang' => [
                'label' => 'Nama barang',
                'attribute' => 'pesanDetail.barang_id',
                'value' => function ($model) {
                    // Ambil relasi barang dari model pesanDetail
                    $barang = $model->pesanDetail->barang;
                    $unit = $barang->unit;
                    return $barang->kode_barang . ' - ' . $barang->nama_barang . ' - ' . $barang->angka . ' ' . ($unit ? $unit->satuan : 'Satuan tidak ditemukan') . ' - ' . $barang->warna;
                }
            ],
            'pesanDetail.barang.harga',
            'pesanDetail.barang.tipe',
            'cek_barang',
            'total_biaya',
            //'catatan',
            //'is_correct',
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