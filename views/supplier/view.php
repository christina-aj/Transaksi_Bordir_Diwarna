<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Supplier $model */

$this->title = $model->supplier_id;
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'supplier_id',
            'nama',
            'notelfon',
            'alamat',
            'kota',
            'kodepos',
        ],
    ]) ?>
    <div>
        <?= Html::a('Update', ['update', 'supplier_id' => $model->supplier_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'supplier_id' => $model->supplier_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['supplier/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>