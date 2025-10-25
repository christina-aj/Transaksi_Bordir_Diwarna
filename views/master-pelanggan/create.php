<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPelanggan $model */

$this->title = 'Create Master Pelanggan';
$this->params['breadcrumbs'][] = ['label' => 'Master Pelanggans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-pelanggan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
