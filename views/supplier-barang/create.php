<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarang $model */

$this->title = 'Create Supplier Barang';
$this->params['breadcrumbs'][] = ['label' => 'Supplier Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-barang-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
