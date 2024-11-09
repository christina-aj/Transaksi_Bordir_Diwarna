<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barang $modelBarang */

$this->title = 'Update Barang  ';
$this->params['breadcrumbs'][] = ['label' => 'Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelBarang->barang_id, 'url' => ['view', 'barang_id' => $modelBarang->barang_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">


    <?= $this->render('_form-update', [
        'modelBarang' => $modelBarang
    ]) ?>

</div>