<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\EoqRopHistory $model */

$this->title = 'Update Eoq Rop History: ' . $model->eoq_rop_history_id;
$this->params['breadcrumbs'][] = ['label' => 'Eoq Rop Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->eoq_rop_history_id, 'url' => ['view', 'eoq_rop_history_id' => $model->eoq_rop_history_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="eoq-rop-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
