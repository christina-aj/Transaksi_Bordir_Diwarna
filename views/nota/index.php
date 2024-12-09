<?php

use app\models\Nota;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Notasearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Nota';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Nota', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nama_konsumen',
            'tanggal',
            [
                'attribute' => 'barang',
                'format' => 'raw',
                'value' => function ($model) {
                    $items = explode(',', $model->barang);
                    $formattedItems = '<ul>';
                    foreach ($items as $item) {
                        $formattedItems .= '<li>' . Html::encode($item) . '</li>';
                    }
                    $formattedItems .= '</ul>';
                    return $formattedItems;
                },
            ],
            [
                'attribute' => 'harga',
                'format' => 'raw',
                'value' => function ($model) {
                    $prices = explode(',', $model->harga);
                    $formattedPrices = '<ul>';
                    foreach ($prices as $price) {
                        $formattedPrices .= '<li>' . Yii::$app->formatter->asCurrency($price, 'IDR') . '</li>';
                    }
                    $formattedPrices .= '</ul>';
                    return $formattedPrices;
                },
            ],
            [
                'attribute' => 'qty',
                'format' => 'raw',
                'value' => function ($model) {
                    $quantities = explode(',', $model->qty);
                    $formattedQuantities = '<ul>';
                    foreach ($quantities as $quantity) {
                        $formattedQuantities .= '<li>' . Html::encode($quantity) . '</li>';
                    }
                    $formattedQuantities .= '</ul>';
                    return $formattedQuantities;
                },
            ],
            'total_qty',
            [
                'attribute' => 'total_harga',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->total_harga, 'IDR');
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Nota $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'nota_id' => $model->nota_id]);
                }
            ],
        ],
    ]); ?>

</div>
