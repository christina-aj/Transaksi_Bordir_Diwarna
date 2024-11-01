<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $modelPemesanan */

$this->title = 'Update Pemesanan: ' . $modelPemesanan->pemesanan_id;
$this->params['breadcrumbs'][] = ['label' => 'Pemesanans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelPemesanan->pemesanan_id, 'url' => ['view', 'pemesanan_id' => $modelPemesanan->pemesanan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelPemesanan' => $modelPemesanan,
        'modelDetails' => $modelDetails,
    ]) ?>

</div>