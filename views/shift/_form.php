<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Shift $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shift-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>
    
    <?= $form->field($model, 'tanggal')->widget(\kartik\date\DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih tanggal ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy', 
            'todayHighlight' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'shift')->dropDownList([1 => 'Pagi', 2 => 'Sore'], ['prompt' => 'Select Shift']) ?>

    <?= $form->field($model, 'waktu_kerja')->dropDownList(
        [
            '1' => 'Full Shift (1)',
            '0.5' => 'Setengah Shift (0.5)',
            'custom' => 'Custom Time'
        ],
        ['prompt' => 'Select Work Hours', 'id' => 'waktu-kerja-dropdown']
    ) ?>

    <div id="custom-time-fields" style="display: none;">
        <?= $form->field($model, 'start_time')->textInput(['id' => 'start-time-input','placeholder' => 'jam:menit']) ?>
        <?= $form->field($model, 'end_time')->textInput(['id' => 'end-time-input','placeholder' => 'jam:menit']) ?>
    </div>

    <?= $form->field($model, 'waktu_kerja_hidden')->hiddenInput(['id' => 'waktu-kerja-hidden'])->label(false) ?>

    <?= $form->field($model, 'nama_operator')->textInput(['maxlength' => true]) ?>

    <!-- Dropdown for Task Type -->
    <?= Html::label('Jenis Pekerjaan', 'task-type-dropdown', ['class' => 'control-label']) ?>
    <?= Html::dropDownList('task_type', null, ['bordir' => 'Bordir', 'kaos_kaki' => 'Kaos Kaki'], [
        'prompt' => 'Pilih Jenis Pekerjaan',
        'class' => 'form-control',
        'id' => 'task-type-dropdown'
    ]) ?>


    <div id="ganti-fields">
        <?= $form->field($model, 'ganti_benang')->textInput() ?>
        <?= $form->field($model, 'ganti_kain')->textInput() ?>
    </div>

    <?= $form->field($model, 'mulai_istirahat')->textInput(['placeholder' => 'Jam:Menit']) ?>

    <?= $form->field($model, 'selesai_istirahat')->textInput(['placeholder' => 'Jam:Menit']) ?>

    <?= $form->field($model, 'kendala')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['shift/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
$(document).ready(function() {
    $('#task-type-dropdown').on('change', function() {
        var taskType = $(this).val();
        if (taskType === 'bordir') {
            $('#ganti-fields').hide(); 
        } else {
            $('#ganti-fields').show(); 
        }
    });

    
    $('#task-type-dropdown').trigger('change');

    
    $('#waktu-kerja-dropdown').on('change', function() {
        var value = $(this).val();
        if (value === 'custom') {
            $('#custom-time-fields').show();
            $('#waktu-kerja-hidden').val('custom');
        } else {
            $('#custom-time-fields').hide();
            $('#waktu-kerja-hidden').val(value);
        }
    });

    
    $('#start-time-input, #end-time-input').on('change', function() {
        var startTime = $('#start-time-input').val();
        var endTime = $('#end-time-input').val();
        if (startTime && endTime) {
            var start = startTime.split(':');
            var end = endTime.split(':');
            var startMinutes = parseInt(start[0]) * 60 + parseInt(start[1]);
            var endMinutes = parseInt(end[0]) * 60 + parseInt(end[1]);
            var diff = (endMinutes - startMinutes) / 60;
            var percentage = diff / 9;
            $('#waktu-kerja-hidden').val(percentage.toFixed(2));
        }
    });

    
    $('#waktu-kerja-dropdown').trigger('change');
});
JS;
$this->registerJs($script);
?>
