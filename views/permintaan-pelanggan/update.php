<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPelanggan $model */

$this->title = 'Update Permintaan Pelanggan: ' . $model->permintaan_id;
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Pelanggans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->permintaan_id, 'url' => ['view', 'permintaan_id' => $model->permintaan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <?= $this->render('_form', [
        'model' => $model,
        'pelangganList' => $pelangganList,
        'nextKode' => null,
        'detailModels' => $detailModels ?? [],
    ]) ?>

</div>
