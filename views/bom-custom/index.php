<?php

use app\models\BomCustom;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BomCustomSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bom Customs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bom-custom-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bom Custom', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'BOM_custom_id',
            'barang_custom_pelanggan_id',
            'barang_id',
            'qty_per_unit',
            'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, BomCustom $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'BOM_custom_id' => $model->BOM_custom_id]);
                 }
            ],
        ],
    ]); ?>


</div>
