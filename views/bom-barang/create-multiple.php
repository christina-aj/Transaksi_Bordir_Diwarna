<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Barang;
use app\models\Unit;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\BomBarang[] $bombarang */
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
    echo $form->field($bombarang[0], 'barang_id')
        ->dropDownList($dataPost, ['prompt' => 'Pilih Barang', 'id' => 'barang_id']);
    ?>

    <!-- Field qty_BOM -->
    <?= $form->field($bombarang[0], 'qty_BOM')->textInput() ?>

    <!-- Bagian Tabel Dinamis untuk Multiple Input -->
    <div class="bombarang-items">
        <h3>bombarang</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Barang ID</th>
                    <th>Jumlah</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bombarang as $i => $item): ?>
                    <tr>
                        <td>
                            <?= $form->field($item, "[$i]barang_id")->dropDownList($dataPost, ['prompt' => 'Pilih Barang'])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($item, "[$i]qty_BOM")->input('number')->label(false) ?>
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
        <?= Html::a('Back', ['bombarang/index'], ['class' => 'btn btn-secondary']) ?>
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
    // Fungsi untuk menambah baris bombarang baru
    $('.add-item').on('click', function() {
        var index = $('table tbody tr').length;
        var options = JSON.parse('<?= $barangOptions; ?>'); // Parse JSON options dari PHP
        var optionsHtml = '<option value="">Pilih Barang</option>';
        $.each(options, function(value, label) {
            optionsHtml += '<option value="' + value + '">' + label + '</option>';
        });

        var template = `<tr>
            <td><select name="bombarang[${index}][barang_id]" class="form-control">
                ${optionsHtml}
            </select></td>
            <td><input type="number" name="bombarang[${index}][qty_BOM]" class="form-control" /></td>
            <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
        </tr>`;
        $('table tbody').append(template);
    });

    // Fungsi untuk menghapus baris bombarang
    $('table').on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
    });
</script>