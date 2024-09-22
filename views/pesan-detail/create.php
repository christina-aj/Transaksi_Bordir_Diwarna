<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PesanDetail $model */

$this->title = 'Create Pesan Detail';
$this->params['breadcrumbs'][] = ['label' => 'Pesan Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>