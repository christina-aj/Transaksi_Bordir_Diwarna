<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PenggunaanDetail $model */

$this->title = 'Update Penggunaan Detail: ' . $model->gunadetail_id;
$this->params['breadcrumbs'][] = ['label' => 'Penggunaan Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gunadetail_id, 'url' => ['view', 'gunadetail_id' => $model->gunadetail_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="penggunaan-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
