<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MoveAreaForm $model */

$this->title = 'Pindah Barang Antar Area';
$this->params['breadcrumbs'][] = ['label' => 'Stock Gudang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Register JS untuk handle perubahan dropdown dan validasi stock
$this->registerJs("
    $('#moveareaform-barang_id, #moveareaform-area_asal').change(function() {
        updateStockInfo();
    });
    
    function updateStockInfo() {
        var barang_id = $('#moveareaform-barang_id').val();
        var area_asal = $('#moveareaform-area_asal').val();
        
        if (barang_id && area_asal) {
            $.ajax({
                url: '" . \yii\helpers\Url::to(['get-stock']) . "',
                type: 'POST',
                data: {
                    barang_id: barang_id,
                    area_gudang: area_asal,
                    kode: 1
                },
                success: function(data) {
                    $('#stock-info').html('Stock tersedia: <strong>' + data.quantity_akhir + '</strong>').show();
                    $('#moveareaform-jumlah').attr('max', data.quantity_akhir);
                    
                    if (data.quantity_akhir <= 0) {
                        $('#stock-info').removeClass('alert-success').addClass('alert-danger');
                        $('#moveareaform-jumlah').prop('disabled', true);
                        $('.btn-submit').prop('disabled', true);
                    } else {
                        $('#stock-info').removeClass('alert-danger').addClass('alert-success');
                        $('#moveareaform-jumlah').prop('disabled', false);
                        $('.btn-submit').prop('disabled', false);
                    }
                }
            });
        } else {
            $('#stock-info').hide();
        }
    }
    
    // Trigger saat halaman load jika sudah ada nilai
    if ($('#moveareaform-barang_id').val() && $('#moveareaform-area_asal').val()) {
        updateStockInfo();
    }
");
?>

<div class="gudang-move-area">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model->barang_id && $model->area_asal): ?>
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle"></i> Informasi Barang</h5>
            <p><strong>Barang:</strong> <?= Html::encode($model->getNamaBarang()) ?></p>
            <p><strong>Area Asal:</strong> Area <?= $model->area_asal ?></p>
            <p><strong>Stock Tersedia:</strong> <?= $model->getAvailableStock() ?></p>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'barang_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(
            \app\models\Barang::find()->all(), 'barang_id', 'nama_barang'
        ),
        [
            'prompt' => 'Pilih Barang', 
            'readonly' => !empty($model->barang_id), 
            'disabled' => !empty($model->barang_id)
        ]
    ) ?>

    <?= $form->field($model, 'area_asal')->dropDownList(
        [1 => 'Area 1', 2 => 'Area 2', 3 => 'Area 3', 4 => 'Area 4'],
        [
            'prompt' => 'Pilih Area Asal', 
            'readonly' => !empty($model->area_asal), 
            'disabled' => !empty($model->area_asal)
        ]
    ) ?>

    <?= $form->field($model, 'area_tujuan')->dropDownList(
        [1 => 'Area 1', 2 => 'Area 2', 3 => 'Area 3', 4 => 'Area 4'],
        ['prompt' => 'Pilih Area Tujuan']
    ) ?>

    <?= $form->field($model, 'jumlah')->textInput([
        'type' => 'number', 
        'min' => 1,
        'max' => $model->getAvailableStock()
    ]) ?>

    <?= $form->field($model, 'catatan')->textInput([
        'maxlength' => true,
        'placeholder' => 'Catatan tambahan (opsional)'
    ]) ?>

    <!-- Alert untuk menampilkan stock info -->
    <div id="stock-info" class="alert" style="display:none;"></div>

    <div class="form-group">
        <?= Html::submitButton('Pindahkan', ['class' => 'btn btn-success btn-submit']) ?>
        <?= Html::a('Batal', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert {
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.gudang-move-area {
    margin: 40px;

</style>