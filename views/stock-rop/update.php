<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StockRop $model */

$this->title = 'Update Stock Rop: ' . $model->stock_rop_id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Rops', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->stock_rop_id, 'url' => ['view', 'stock_rop_id' => $model->stock_rop_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-rop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
