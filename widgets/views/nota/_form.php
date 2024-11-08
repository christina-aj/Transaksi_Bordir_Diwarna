<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Barangproduksi;

// Pastikan $barangList tersedia dari controller
$itemCount = max(count($model->barangList), 1);

$js = <<<JS
var itemCount = {$itemCount};

function addItem() {
    var template = $('#item-template').html();
    template = template.replace(/\{index\}/g, itemCount);
    $('#items-container').append(template);
    itemCount++;
}

function removeItem(btn) {
    $(btn).closest('.item-row').remove();
}

$('#add-item').on('click', function() {
    addItem();
});

$(document).on('click', '.remove-item', function() {
    removeItem(this);
});
JS;
$this->registerJs($js);
?>

<div class="nota-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_konsumen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>

    <div id="items-container">
        <?php 
        $items = $model->barangList ?: [null];
        foreach ($items as $index => $barang): 
        ?>
            <div class="item-row">
                <?php 
                $barangList = ArrayHelper::map(Barangproduksi::find()->all(), 'nama', 'nama');
                echo $form->field($model, "barangList[]")->dropDownList(
                    $barangList,
                    ['prompt' => 'Pilih Barang', 'value' => $barang]
                ); ?>
                
                <?= $form->field($model, "hargaList[]")->textInput([
                    'type' => 'number', 
                    'value' => $model->hargaList[$index] ?? null
                ]); ?>
                
                <?= $form->field($model, "qtyList[]")->textInput([
                    'type' => 'number', 
                    'value' => $model->qtyList[$index] ?? null
                ]); ?>
                
                <button type="button" class="btn btn-danger remove-item">Hapus</button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="form-group mt-3"> <!-- Add margin-top for spacing -->
        <button type="button" id="add-item" class="btn btn-primary">Tambah Item</button>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['nota/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/template" id="item-template">
    <div class="item-row">
        <?php 
        echo $form->field($model, "barangList[]")->dropDownList(
            $barangList,
            ['prompt' => 'Pilih Barang']
        ); ?>
        <?= $form->field($model, "hargaList[]")->textInput(['type' => 'number']) ?>
        <?= $form->field($model, "qtyList[]")->textInput(['type' => 'number']) ?>
        <button type="button" class="btn btn-danger remove-item">Hapus</button>
    </div>
</script>
