<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\EoqRopHistory $model */

$this->title = 'Create Eoq Rop History';
$this->params['breadcrumbs'][] = ['label' => 'Eoq Rop Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="eoq-rop-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
