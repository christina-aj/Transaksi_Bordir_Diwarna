<?php

use app\models\Pemesanan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PemesananSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pemesanan Bahan Produksi';
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

            'pemesanan_id' =>
            [
                'label' => 'Kode Pemesanan',
                'attribute' => 'pemesanan_id',
                'value' => function ($model) {
                    return $model->getFormattedOrderId(); // Call the method to get the formatted ID
                },
            ],
            'user_id' => [
                'label' => 'Nama Pemesan',
                'attribute' => 'user_id',
                'value' => 'user.nama_pengguna',
            ],
            'tanggal' => [
                'attribute' => 'tanggal',
                'format' => ['date', 'php:d-M-Y'], // Mengubah format menjadi dd-mm-yyyy
            ],
            'total_item',
            // 'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function ($action, Pemesanan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pemesanan_id' => $model->pemesanan_id]);
                }
            ],
        ],
    ]); ?>


</div>