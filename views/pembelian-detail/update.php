<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PembelianDetail $model */

$this->title = 'Update Buku Kas: ' . $model->belidetail_id;
$this->params['breadcrumbs'][] = ['label' => 'Pembelian Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->belidetail_id, 'url' => ['view', 'belidetail_id' => $model->belidetail_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>