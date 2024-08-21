<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Mesin $model */

$this->title = 'Create Mesin';
$this->params['breadcrumbs'][] = ['label' => 'Mesins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mesin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
