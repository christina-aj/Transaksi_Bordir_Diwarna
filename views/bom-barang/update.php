<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BomBarang $model */

$this->title = 'Update Bom Barang: ' . $model->BOM_barang_id;
$this->params['breadcrumbs'][] = ['label' => 'Bom Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->BOM_barang_id, 'url' => ['view', 'BOM_barang_id' => $model->BOM_barang_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bom-barang-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
