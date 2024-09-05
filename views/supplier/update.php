<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Supplier $model */

$this->title = 'Update Supplier: ' . $model->supplier_id;
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->supplier_id, 'url' => ['view', 'supplier_id' => $model->supplier_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>