<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPelanggan $model */

$this->title = 'Update Master Pelanggan: ' . $model->pelanggan_id;
$this->params['breadcrumbs'][] = ['label' => 'Master Pelanggans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pelanggan_id, 'url' => ['view', 'pelanggan_id' => $model->pelanggan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-pelanggan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
