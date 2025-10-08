<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DetailPermintaan $model */

$this->title = 'Update Detail Permintaan: ' . $model->detail_permintaan_id;
$this->params['breadcrumbs'][] = ['label' => 'Detail Permintaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->detail_permintaan_id, 'url' => ['view', 'detail_permintaan_id' => $model->detail_permintaan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="detail-permintaan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
