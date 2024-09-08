<?php

use app\models\PembelianDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

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
            'pembelian.tanggal',
            'pembelian.kode_struk',
            'barang_id',
            'barang.kode_barang',
            'barang.nama_barang',
            $showFullContent ? 'harga_barang' : null,
            'quantity_barang',
            $showFullContent ? 'total_biaya' : null,
            //'catatan',
            'langsung_pakai',
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