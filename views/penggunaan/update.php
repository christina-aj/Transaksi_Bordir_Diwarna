<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $model */

$this->title = 'Update Penggunaan: ' . $model->penggunaan_id;
$this->params['breadcrumbs'][] = ['label' => 'Penggunaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->penggunaan_id, 'url' => ['view', 'penggunaan_id' => $model->penggunaan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>