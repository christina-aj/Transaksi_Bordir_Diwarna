<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\BarangCustomPelanggan $model */

$this->title = $model->barang_custom_pelanggan_id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Custom Pelanggans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="barang-custom-pelanggan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'barang_custom_pelanggan_id' => $model->barang_custom_pelanggan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'barang_custom_pelanggan_id' => $model->barang_custom_pelanggan_id], [
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
            'barang_custom_pelanggan_id',
            'pelanggan_id',
            'kode_barang_custom',
            'nama_barang_custom',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
