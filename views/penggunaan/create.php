<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $model */

$this->title = 'Create Penggunaan';
$this->params['breadcrumbs'][] = ['label' => 'Penggunaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="pc-content">

    <?= $this->render('_form', [
        'modelPenggunaan' => $modelPenggunaan,
        'modelDetails' => $modelDetails,
        'permintaanId' => $permintaanId,
    ]) ?>
    

</div>

