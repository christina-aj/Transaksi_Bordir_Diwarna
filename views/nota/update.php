<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Nota $model */

$this->title = 'Update Nota: ' . $model->nota_id;
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nota_id, 'url' => ['view', 'nota_id' => $model->nota_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
