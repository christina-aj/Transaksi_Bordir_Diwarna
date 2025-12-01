<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksi $model */

$this->title = 'Update Barang produksi ';
$this->params['breadcrumbs'][] = ['label' => 'Barangproduksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->barang_produksi_id, 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update', [
        'model' => $model,
        'barangList' => $barangList,
        'existingBom' => $existingBom
    ]) ?>

</div>
