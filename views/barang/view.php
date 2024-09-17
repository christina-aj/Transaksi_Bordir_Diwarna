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
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'barang_id',
            'kode_barang',
            'nama_barang',
            'angka',
            'unit_id',
            'harga',
            'tipe',
            'warna',
            'supplier.nama',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    <div>
        <?= Html::a('Update', ['update', 'barang_id' => $model->barang_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'barang_id' => $model->barang_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],

        ]) ?>
        <?= Html::a('Back', ['barang/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>