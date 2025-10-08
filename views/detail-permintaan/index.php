<?php

use app\models\DetailPermintaan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DetailPermintaanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Detail Permintaans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detail-permintaan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Detail Permintaan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'detail_permintaan_id',
            'permintaan_penjualan_id',
            'barang_produksi_id',
            'qty_permintaan',
            'catatan',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, DetailPermintaan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'detail_permintaan_id' => $model->detail_permintaan_id]);
                 }
            ],
        ],
    ]); ?>


</div>
