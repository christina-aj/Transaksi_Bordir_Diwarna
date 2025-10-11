<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\RiwayatPenjualan $model */

$this->title = 'Create Riwayat Penjualan';
$this->params['breadcrumbs'][] = ['label' => 'Riwayat Penjualans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    
    <?= $this->render('_form', [
        'models' => $modelRiwayatPenjualans,
    ]) ?>

</div>
