<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StockRop $model */

$this->title = 'Create Stock Rop';
$this->params['breadcrumbs'][] = ['label' => 'Stock Rops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-rop-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
