<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksi $model */

$this->title = 'Update Barangproduksi: ' . $model->barang_id;
$this->params['breadcrumbs'][] = ['label' => 'Barangproduksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->barang_id, 'url' => ['view', 'barang_id' => $model->barang_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="barangproduksi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
