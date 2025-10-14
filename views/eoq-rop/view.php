<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\EoqRop $model */

$this->title = $model->EOQ_ROP_id;
$this->params['breadcrumbs'][] = ['label' => 'Eoq Rops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="eoq-rop-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'EOQ_ROP_id' => $model->EOQ_ROP_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'EOQ_ROP_id' => $model->EOQ_ROP_id], [
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
            'EOQ_ROP_id',
            'barang_id',
            'total_bom',
            'biaya_pesan_snapshot',
            'biaya_simpan_snapshot',
            'safety_stock_snapshot',
            'lead_time_snapshot:datetime',
            'demand_snapshot',
            'total_biaya_persediaan',
            'hasil_eoq',
            'hasil_rop',
            'periode',
            'created_at',
        ],
    ]) ?>

</div>
