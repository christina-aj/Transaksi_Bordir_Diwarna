<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\LaporanKeluar $model */

$this->title = 'Create Laporan Keluar';
$this->params['breadcrumbs'][] = ['label' => 'Laporan Keluars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
