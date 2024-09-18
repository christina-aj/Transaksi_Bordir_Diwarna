<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $model */

$this->title = 'Update Pemesanan: ' . $model->pemesanan_id;
$this->params['breadcrumbs'][] = ['label' => 'Pemesanans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pemesanan_id, 'url' => ['view', 'pemesanan_id' => $model->pemesanan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>