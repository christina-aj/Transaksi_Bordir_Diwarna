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

    <?= $form->field($model, 'shift')->dropDownList([1 => '1', 2 => '2'], ['prompt' => 'Select Shift']) ?>

    <?= $form->field($model, 'waktu_kerja')->dropDownList(
        [
            '1' => 'Full Shift (1)',
            '0.5' => 'Setengah Shift (0.5)',
            'custom' => 'Custom Time'
        ],
        ['prompt' => 'Select Work Hours', 'id' => 'waktu-kerja-dropdown']
    ) ?>

    
    <div id="custom-time-fields" style="display: none;">
        <?= $form->field($model, 'start_time')->textInput( ['id' => 'start-time-input','placeholder' => 'jam:menit']) ?>
        <?= $form->field($model, 'end_time')->textInput( ['id' => 'end-time-input','placeholder' => 'jam:menit']) ?>
    </div>

    <?= $form->field($model, 'waktu_kerja_hidden')->hiddenInput(['id' => 'waktu-kerja-hidden'])->label(false) ?>

    <?= $form->field($model, 'nama_operator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mulai_istirahat')->textInput(['placeholder' => 'jam:menit:detik']) ?>

    <?= $form->field($model, 'selesai_istirahat')->textInput(['placeholder' => 'jam:menit:detik']) ?>

    <?= $form->field($model, 'kendala')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ganti_benang')->textInput() ?>

    <?= $form->field($model, 'ganti_kain')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['shift/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

    $('form').on('submit', function() {
        var day = $('#day-input').val();
        var month = $('#month-input').val();
        var year = $('#year-input').val();
        
        
        var formattedDate = day + '-' + month + '-' + year;
        
       
        $('#tanggal-hidden').val(formattedDate);
    });
</script>


<?php
$script = <<< JS
$(document).ready(function() {
    function handleWorkHoursSelection() {
        var value = $('#waktu-kerja-dropdown').val();
        console.log('Work hours selected:', value); 
        if (value === 'custom') {
            $('#custom-time-fields').show();
            $('#waktu-kerja-hidden').val('custom');
        } else {
            $('#custom-time-fields').hide();
            $('#waktu-kerja-hidden').val(value);
        }
    }

    $('#waktu-kerja-dropdown').on('change', handleWorkHoursSelection);

    $('#start-time-input, #end-time-input').on('change', function() {
        var startTime = $('#start-time-input').val();
        var endTime = $('#end-time-input').val();
        console.log('Start time:', startTime, 'End time:', endTime); 
        if (startTime && endTime) {
           
            var start = startTime.split(':');
            var end = endTime.split(':');
            var startMinutes = parseInt(start[0]) * 60 + parseInt(start[1]);
            var endMinutes = parseInt(end[0]) * 60 + parseInt(end[1]);

            var diff = (endMinutes - startMinutes) / 60;
            var percentage = diff / 9; 
            console.log('Calculated percentage:', percentage.toFixed(2)); 
            $('#waktu-kerja-hidden').val(percentage.toFixed(2));
        }
    });

   
    handleWorkHoursSelection();
});
JS;
$this->registerJs($script);
?>