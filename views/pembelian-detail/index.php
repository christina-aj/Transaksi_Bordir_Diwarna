<?php

use app\models\PembelianDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PembelianDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pembelian Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pembelian-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pembelian Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'belidetail_id',
            'pembelian_id',
            'pesandetail_id',
            'cek_barang',
            'total_biaya',
            //'catatan',
            //'is_correct',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PembelianDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'belidetail_id' => $model->belidetail_id]);
                 }
            ],
        ],
    ]); ?>


</div>
