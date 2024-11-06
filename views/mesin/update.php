<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Mesin $model */

$this->title = 'Update Mesin: ' . $model->mesin_id;
$this->params['breadcrumbs'][] = ['label' => 'Mesins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mesin_id, 'url' => ['view', 'mesin_id' => $model->mesin_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
