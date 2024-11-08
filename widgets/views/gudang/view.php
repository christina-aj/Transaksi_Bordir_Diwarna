<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Gudang $model */

$this->title = $model->id_gudang;
$this->params['breadcrumbs'][] = ['label' => 'Gudangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_gudang',
            'tanggal',
            'barang_id',
            'user_id',
            'quantity_awal',
            'quantity_masuk',
            'quantity_keluar',
            'quantity_akhir',
            'catatan',
            'created_at:datetime',
            'update_at:datetime',
        ],
    ]) ?>
    <div>
        <?= Html::a('Update', ['update', 'id_gudang' => $model->id_gudang], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_gudang' => $model->id_gudang], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['gudang/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>