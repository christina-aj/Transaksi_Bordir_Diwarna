<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksi $model */

$this->title = 'Update Barangproduksi ';
$this->params['breadcrumbs'][] = ['label' => 'Barangproduksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->barang_id, 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
