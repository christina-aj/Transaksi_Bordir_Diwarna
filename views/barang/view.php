<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Barang $model */

$this->title = $model->barang_id;
$this->params['breadcrumbs'][] = ['label' => 'Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="barang-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'barang_id' => $model->barang_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'barang_id' => $model->barang_id], [
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
            'barang_id',
            'kode_barang',
            'nama_barang',
            'unit_id',
            'harga',
            'tipe',
            'warna',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>