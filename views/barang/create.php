<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barang $model */

$this->title = 'Buat List Barang';
$this->params['breadcrumbs'][] = ['label' => 'Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <?= $this->render('_form', [
        'modelBarangs' => $modelBarangs,
    ]) ?>

</div>