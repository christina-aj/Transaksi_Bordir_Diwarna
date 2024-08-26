<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barang $model */

$this->title = 'Update Barang: ' . $model->barang_id;
$this->params['breadcrumbs'][] = ['label' => 'Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->barang_id, 'url' => ['view', 'barang_id' => $model->barang_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <h1>Update Informasi barang</h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>