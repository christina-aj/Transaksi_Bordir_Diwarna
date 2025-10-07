<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BomBarang $model */

$this->title = 'Create Bom Barang';
$this->params['breadcrumbs'][] = ['label' => 'Bom Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bom-barang-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
