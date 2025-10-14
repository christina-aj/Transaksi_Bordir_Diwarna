<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPenjualan $model */

$this->title = 'Create Permintaan Penjualan';
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Penjualans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">

    <?= $this->render('_form', [
        'modelPermintaan' => $modelPermintaan,
        'modelDetails' => $modelDetails,
    ]) ?>

</div>
