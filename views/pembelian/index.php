<?php

use app\models\Pembelian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;

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

            'pembelian_id',
            // 'pemesanan_id',
            'pemesanan.pemesanan_id',
            'pemesanan.user_id',
            'pemesanan.tanggal',
            'pemesanan.total_item',
            'user_id',
            'total_biaya',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Pembelian $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pembelian_id' => $model->pembelian_id]);
                }
            ],
        ],
    ]); ?>


</div>