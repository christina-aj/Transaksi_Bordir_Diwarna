<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BomCustom $model */

$this->title = 'Update Bom Custom: ' . $model->BOM_custom_id;
$this->params['breadcrumbs'][] = ['label' => 'Bom Customs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->BOM_custom_id, 'url' => ['view', 'BOM_custom_id' => $model->BOM_custom_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bom-custom-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
