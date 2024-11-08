<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksi $model */

$this->title = 'Create Barang produksi';
$this->params['breadcrumbs'][] = ['label' => 'Barangproduksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
