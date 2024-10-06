<?php

use app\models\Nota;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Notasearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Notas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Nota', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nota_id',
            'nama_konsumen',
            'tanggal',
            [
                'attribute' => 'barang',
                'format' => 'raw', 
                'value' => function ($model) {
                    return nl2br(str_replace(',', "<br/>", $model->barang)); 
                },
            ],
            [
                'attribute' => 'harga',
                'format' => 'raw', 
                'value' => function ($model) {
                    return nl2br(str_replace(',', "<br/>", $model->harga)); 
                },
            ],
            [
                'attribute' => 'qty',
                'format' => 'raw', 
                'value' => function ($model) {
                    return nl2br(str_replace(',', "<br/>", $model->qty)); 
                },
            ],
            'total_qty',
            'total_harga',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Nota $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'nota_id' => $model->nota_id]);
                }
            ],
        ],
    ]); ?>

</div>
