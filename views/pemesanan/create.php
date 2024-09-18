<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $model */

$this->title = 'Create Pemesanan';
$this->params['breadcrumbs'][] = ['label' => 'Pemesanans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>