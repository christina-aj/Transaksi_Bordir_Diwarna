<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Shift $model */

$this->title = 'Create Shift';
$this->params['breadcrumbs'][] = ['label' => 'Shifts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
