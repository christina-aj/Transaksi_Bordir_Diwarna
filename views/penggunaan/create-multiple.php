<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Barang;
use app\models\Unit;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan[] $penggunaan */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pc-content">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Bagian Dropdown Barang dengan Unit -->
    <?php
    $dataBarang = Barang::find()->asArray()->all();
    $dataUnit = Unit::find()->asArray()->all();

    $dataPost = ArrayHelper::map($dataBarang, 'barang_id', function ($model) use ($dataUnit) {
        // Cari satuan yang sesuai dengan barang ini
        $unit = ArrayHelper::getValue($dataUnit, array_search($model['unit_id'], array_column($dataUnit, 'unit_id')));

        // Jika ditemukan satuan, masukkan ke dalam output
        $unitName = $unit ? $unit['satuan'] : 'Satuan tidak ditemukan';

        // Format string dengan data dari Barang dan Satuan
        return $model['barang_id'] . ' - ' . $model['kode_barang'] . ' - ' . $model['nama_barang'] . ' - ' . $model['angka'] . ' ' . $unitName . ' - ' . $model['warna'];
    });
    echo $form->field($penggunaan[0], 'barang_id')
        ->dropDownList($dataPost, ['prompt' => 'Pilih Barang', 'id' => 'barang_id']);
    ?>

    <!-- Field User ID dan Info User -->
    <?= $form->field($penggunaan[0], 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <?php
    // Menampilkan user_id dan nama_pengguna di satu text field (readonly)
    $user_info = Yii::$app->user->id . ' - ' . Yii::$app->user->identity->nama_pengguna;
    echo $form->field($penggunaan[0], 'user_info')->textInput(['value' => $user_info, 'readonly' => true, 'label' => 'User']);
    ?>

    <!-- Widget DatePicker untuk Field Tanggal -->
    <?= $form->field($penggunaan[0], 'tanggal')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'dd-mm-yyyy'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy',
        ],
    ]); ?>

    <!-- Field Qty -->
    <?= $form->field($penggunaan[0], 'qty')->textInput() ?>

    <!-- Bagian Tabel Dinamis untuk Multiple Input -->
    <div class="penggunaan-items">
        <h3>penggunaan</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Barang ID</th>
                    <th>User ID</th>
                    <th>Tanggal</th>
                    <th>Qty</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($penggunaan as $i => $item): ?>
                    <tr>
                        <td>
                            <?= $form->field($item, "[$i]barang_id")->dropDownList($dataPost, ['prompt' => 'Pilih Barang'])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($item, "[$i]user_id")->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($item, "[$i]tanggal")->widget(DatePicker::classname(), [
                                'options' => ['placeholder' => 'dd-mm-yyyy'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                ],
                            ])->label(false); ?>
                        </td>
                        <td>
                            <?= $form->field($item, "[$i]qty")->input('number')->label(false) ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-success add-item">Add Item</button>
    </div>

    <!-- Tombol Submit -->
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['penggunaan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Ambil opsi dropdown untuk barang_id dalam format JSON
$barangOptions = json_encode($dataPost);

// Ambil user ID dari Yii::$app->user
$userId = Yii::$app->user->id; // Simpan ke variabel PHP untuk digunakan dalam JavaScript
?>

<script>
    // Fungsi untuk menambah baris penggunaan baru
    $('.add-item').on('click', function() {
        var index = $('table tbody tr').length;
        var options = JSON.parse('<?= $barangOptions; ?>'); // Parse JSON options dari PHP
        var optionsHtml = '<option value="">Pilih Barang</option>';
        $.each(options, function(value, label) {
            optionsHtml += '<option value="' + value + '">' + label + '</option>';
        });

        var template = `<tr>
            <td><select name="penggunaan[${index}][barang_id]" class="form-control">
                ${optionsHtml}
            </select></td>
            <td><input type="hidden" name="penggunaan[${index}][user_id]" value="<?= $userId; ?>" /></td> <!-- Eksekusi PHP untuk mengambil user ID -->
            <td><input type="date" name="penggunaan[${index}][tanggal]" class="form-control" /></td>
            <td><input type="number" name="penggunaan[${index}][jumlah_digunakan]" class="form-control" /></td>
            <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
        </tr>`;
        $('table tbody').append(template);
    });

    // Fungsi untuk menghapus baris penggunaan
    $('table').on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
    });
</script>