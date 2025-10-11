<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DataPerhitungan $model */

$this->title = 'Create Data Perhitungan';
$this->params['breadcrumbs'][] = ['label' => 'Data Perhitungans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-perhitungan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
