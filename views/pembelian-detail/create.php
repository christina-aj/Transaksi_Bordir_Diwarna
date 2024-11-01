<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PembelianDetail $model */

$this->title = 'Create Pembelian Detail';
$this->params['breadcrumbs'][] = ['label' => 'Pembelian Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>