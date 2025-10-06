<?php

use app\models\PenggunaanDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PenggunaanDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Penggunaan Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penggunaan-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Penggunaan Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'gunadetail_id',
            'penggunaan_id',
            'barang_id',
            'jumlah_digunakan',
            'catatan',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PenggunaanDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'gunadetail_id' => $model->gunadetail_id]);
                 }
            ],
        ],
    ]); ?>


</div>
