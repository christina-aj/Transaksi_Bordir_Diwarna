<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pembelian $model */

$this->title = 'Update Pembelian: ' . $model->pembelian_id;
$this->params['breadcrumbs'][] = ['label' => 'Pembelians', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pembelian_id, 'url' => ['view', 'pembelian_id' => $model->pembelian_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>