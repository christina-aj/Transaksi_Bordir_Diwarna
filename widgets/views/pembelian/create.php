<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pembelian $model */

$this->title = 'Create Pembelian';
$this->params['breadcrumbs'][] = ['label' => 'Pembelians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>