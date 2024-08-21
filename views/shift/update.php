<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Shift $model */

$this->title = 'Update Shift: ' . $model->shift_id;
$this->params['breadcrumbs'][] = ['label' => 'Shifts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->shift_id, 'url' => ['view', 'shift_id' => $model->shift_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
