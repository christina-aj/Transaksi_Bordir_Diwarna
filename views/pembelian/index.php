<?php

use app\models\Pembelian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PembelianSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pembelian Barang Produksi';
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
            'kode_pembelian' => [
                'label' => 'Kode pembelian',
                'attribute' => 'kode_pembelian',
                'value' => function ($model) {
                    return $model->getFormattedBuyOrderId(); // Call the method to get the formatted ID
                },
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Cari Kode Pembelian',
                ],
            ],
            'kode_pemesanan' => [
                'label' => 'Kode pemesanan',
                'attribute' => 'kode_pemesanan',
                'value' => function ($model) {
                    $pemesanan = $model->pemesanan;
                    return $pemesanan->getFormattedOrderId(); // Call the method to get the formatted ID
                },
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Cari Kode Pembelian',
                ],
            ],
            // 'pemesanan_id',
            // 'pemesanan.pemesanan_id',
            // 'pemesanan.user_id',
            'pemesanan.user.nama_pengguna' => [
                'label' => 'Nama Pemesan',
                'attribute' => 'nama_pemesan',
                'value' => 'pemesanan.user.nama_pengguna',
            ],
            'pemesanan.tanggal' => [
                'label' => 'Tanggal Pemesanan',
                'attribute' => 'tanggal',
                'value' => 'pemesanan.tanggal',
            ],
            'pemesanan.total_item' => [
                'label' => 'Total Item',
                'attribute' => 'total_item',
                'value' => 'pemesanan.total_item'
            ],
            // 'user_id',
            'total_biaya',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function ($action, Pembelian $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pembelian_id' => $model->pembelian_id]);
                }
            ],
        ],
    ]); ?>


</div>