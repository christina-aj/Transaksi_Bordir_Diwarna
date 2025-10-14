<?php

use app\models\PermintaanDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PermintaanDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Permintaan Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permintaan-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Permintaan Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'permintaan_detail_id',
            'permintaan_penjualan_id',
            'barang_produksi_id',
            'qty_permintaan',
            'catatan',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PermintaanDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'permintaan_detail_id' => $model->permintaan_detail_id]);
                 }
            ],
        ],
    ]); ?>


</div>
