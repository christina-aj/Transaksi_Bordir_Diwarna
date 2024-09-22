<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PesanDetail $model */

$this->title = 'Update Pesan Detail: ' . $model->pesandetail_id;
$this->params['breadcrumbs'][] = ['label' => 'Pesan Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pesandetail_id, 'url' => ['view', 'pesandetail_id' => $model->pesandetail_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>