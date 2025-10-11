<?php

use app\models\DataPerhitungan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DataPerhitunganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Data Perhitungans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-perhitungan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Data Perhitungan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'data_perhitungan_id',
            'barang_id',
            'biaya_pesan',
            'biaya_simpan',
            'safety_stock',
            //'lead_time_rerata:datetime',
            //'periode_mulasi',
            //'periode_selesai',
            //'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, DataPerhitungan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'data_perhitungan_id' => $model->data_perhitungan_id]);
                 }
            ],
        ],
    ]); ?>


</div>
