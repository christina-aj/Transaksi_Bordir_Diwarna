<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DetailPermintaan $model */

$this->title = 'Create Detail Permintaan';
$this->params['breadcrumbs'][] = ['label' => 'Detail Permintaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detail-permintaan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
