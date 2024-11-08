<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->nota_id;
$this->params['breadcrumbs'][] = ['label' => 'Notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

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
            'target' => '_blank',
        ]) ?>
        <?= Html::a('Back', ['nota/index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nota_id',
            'nama_konsumen',
            'tanggal',
            'total_qty',
            [
                'attribute' => 'total_harga',
                'value' => Yii::$app->formatter->asCurrency($model->total_harga, 'IDR'),
            ],
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
                    <td><?= Yii::$app->formatter->asCurrency($model->hargaList[$index], 'IDR') ?></td>
                    <td><?= Html::encode($model->qtyList[$index]) ?></td>
                    <td><?= Yii::$app->formatter->asCurrency($model->hargaList[$index] * $model->qtyList[$index], 'IDR') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
