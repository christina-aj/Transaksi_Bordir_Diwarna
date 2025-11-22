<?php

use app\models\EoqRopHistory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EoqRopHistorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Eoq Rop Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="eoq-rop-history-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Eoq Rop History', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'eoq_rop_history_id',
            'barang_id',
            'biaya_pesan_snapshot',
            'biaya_simpan_snapshot',
            'safety_stock_snapshot',
            //'lead_time_snapshot:datetime',
            //'demand_snapshot',
            //'total_biaya_perediaan',
            //'hasil_eoq',
            //'hasil_rop',
            //'periode',
            //'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, EoqRopHistory $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'eoq_rop_history_id' => $model->eoq_rop_history_id]);
                 }
            ],
        ],
    ]); ?>


</div>
