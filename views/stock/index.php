<?php

use app\models\Stock;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\StockSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'stock_id',
            [
                'attribute' => 'tambah_stock',
                'format' => ['date', 'php:d-M-Y'], // Mengubah format ke dd-mm-yyyy
                'label' => 'Tanggal',
            ],
            // 'tambah_stock',
            'barang_id',
            'barang.kode_barang',
            'barang.nama_barang',
            'quantity_awal',
            'quantity_masuk',
            'quantity_keluar',
            'quantity_akhir',
            // 'user_id',
            'user.nama_pengguna',
            'is_ready',
            'is_new',
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