<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->nota_id;
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="nota-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'nota_id' => $model->nota_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'nota_id' => $model->nota_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Print', ['print', 'nota_id' => $model->nota_id], [
        'class' => 'btn btn-info',
        'target' => '_blank', // Open in a new tab
        ]) ?>>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nota_id',
            'nama_konsumen',
            'tanggal',
            'total_qty',
            'total_harga',
        ],
    ]) ?>

    <h2>Items</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->barangList as $index => $barang): ?>
                <tr>
                    <td><?= Html::encode($barang) ?></td>
                    <td><?= Html::encode($model->hargaList[$index]) ?></td>
                    <td><?= Html::encode($model->qtyList[$index]) ?></td>
                    <td><?= Html::encode($model->hargaList[$index] * $model->qtyList[$index]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
