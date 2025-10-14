<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PermintaanDetail $model */

$this->title = 'Update Permintaan Detail: ' . $model->permintaan_detail_id;
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->permintaan_detail_id, 'url' => ['view', 'permintaan_detail_id' => $model->permintaan_detail_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permintaan-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
