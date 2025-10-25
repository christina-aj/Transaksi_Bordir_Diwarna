<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\BomCustom $model */

$this->title = $model->BOM_custom_id;
$this->params['breadcrumbs'][] = ['label' => 'Bom Customs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bom-custom-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'BOM_custom_id' => $model->BOM_custom_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'BOM_custom_id' => $model->BOM_custom_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'BOM_custom_id',
            'barang_custom_pelanggan_id',
            'barang_id',
            'qty_per_unit',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
