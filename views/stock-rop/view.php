<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\StockRop $model */

$this->title = $model->stock_rop_id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Rops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="stock-rop-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'stock_rop_id' => $model->stock_rop_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'stock_rop_id' => $model->stock_rop_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'stock_rop_id',
            'barang_id',
            'periode',
            'stock_barang',
            'safety_stock',
            'jumlah_eoq',
            'jumlah_rop',
            'pesan_barang',
        ],
    ]) ?>

</div>
