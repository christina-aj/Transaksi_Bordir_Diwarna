<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pembelian $modelPembelian */
/** @var app\models\PembelianDetail[] $modelDetails */

$this->title = 'Update Pembelian Kode: ' . $modelPembelian->getFormattedBuyOrderId();
$this->params['breadcrumbs'][] = ['label' => 'Pembelians', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelPembelian->pembelian_id, 'url' => ['view', 'pembelian_id' => $modelPembelian->pembelian_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">
    <?= $this->render('_form', [
        'model' => $modelPembelian,
        'modelDetails' => $modelDetails,
    ]) ?>

</div>