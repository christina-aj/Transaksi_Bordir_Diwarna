<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\EoqRop $model */

$this->title = 'Create Eoq Rop';
$this->params['breadcrumbs'][] = ['label' => 'Eoq Rops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="eoq-rop-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
