<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $modelPenggunan */

$this->title = 'Update Penggunaan: ' . $modelPenggunaan->penggunaan_id;
$this->params['breadcrumbs'][] = ['label' => 'Penggunaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelPenggunaan->penggunaan_id, 'url' => ['view', 'penggunaan_id' => $modelPenggunaan->penggunaan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">
    <?= $this->render('_form', [
        'modelPenggunaan' => $modelPenggunaan,
        'modelDetails' => $modelDetails,
    ]) ?>

</div>
