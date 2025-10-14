<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\EoqRop $model */

$this->title = 'Update Eoq Rop: ' . $model->EOQ_ROP_id;
$this->params['breadcrumbs'][] = ['label' => 'Eoq Rops', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->EOQ_ROP_id, 'url' => ['view', 'EOQ_ROP_id' => $model->EOQ_ROP_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="eoq-rop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
