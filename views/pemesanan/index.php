<?php

use app\models\Pemesanan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Unit;

/** @var yii\web\View $this */
/** @var app\models\PemesananSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Buat Pesanan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pemesanan', ['create'], ['class' => 'btn btn-success']) ?>
        <!-- <?= Html::a('Create Multiple Pemesanan', ['create-multiple'], ['class' => 'btn btn-success']) ?> -->
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pemesanan_id',
            // 'barang_id',
            'tanggal',
            'user.nama_pengguna' => [
                'attribute' => 'user_id',
                'label' => 'nama Pengguna',
                'value' => 'user.nama_pengguna',
            ],
            'barang.nama_barang' => [
                'attribute' => 'barang_id', // Bisa menggunakan attribute lain sesuai kebutuhan
                'label' => 'Detail Barang',
                'value' => function ($model) {
                    $barang = $model->barang;
                    $unit = $barang->unit;
                    return $barang->kode_barang . ' - ' . $barang->nama_barang . ' - ' . $barang->angka . ' ' . ($unit ? $unit->satuan : 'Satuan tidak ditemukan') . ' - ' . $barang->warna;
                }
            ],
            // 'user_id',
            'qty',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Pemesanan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pemesanan_id' => $model->pemesanan_id]);
                }
            ],
        ],
    ]); ?>


</div>