<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BomBarang $model */

$this->title = 'Update Bom Barang: ' . $modelBom->BOM_barang_id;
$this->params['breadcrumbs'][] = ['label' => 'Bom Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelBom->BOM_barang_id, 'url' => ['view', 'BOM_barang_id' => $modelBom->BOM_barang_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <?= $this->render('_form', [
        'modelBom' => $modelBom,
        'modelDetails' => $modelDetails,
    ]) ?>

</div>
