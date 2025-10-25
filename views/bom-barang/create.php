<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BomBarang $model */

$this->title = 'Create Bom Barang';
$this->params['breadcrumbs'][] = ['label' => 'Bom Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <?= $this->render('_form', [
        'modelBom' => $modelBom,
        'modelDetails' => $modelDetails,
    ]) ?>

</div>
