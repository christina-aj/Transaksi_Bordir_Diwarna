<?php

use app\models\Barang;
use app\models\Unit;
use yii\bootstrap5\Alert as Bootstrap5Alert;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var app\models\Barang $modelBarangmodel */
/** @var yii\widgets\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProvider */


?>

<div class="barang-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <!-- Tombol Consumable dan Non Consumable -->
            <?= Html::button('Consumable', [
                'class' => 'btn btn-outline-primary',
                'id' => 'toggle-consumable-button',
            ]) ?>
            <?= Html::button('Non Consumable', [
                'class' => 'btn btn-outline-secondary',
                'id' => 'toggle-non-consumable-button',
            ]) ?>
        </div>

        <div class="card-body mx-4">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($modelBarang, 'kode_barang')->textInput(['maxlength' => true]) ?>

            <?= $form->field($modelBarang, 'nama_barang')->textInput(['maxlength' => true]) ?>

            <?= $form->field($modelBarang, 'angka')->textInput(['maxlength' => true]) ?>

            <?= $form->field($modelBarang, 'unit_id')->dropDownList(
                ArrayHelper::map(Unit::find()->asArray()->all(), 'unit_id', 'satuan'),
                ['prompt' => 'Pilih Satuan']
            ) ?>

            <?= $form->field($modelBarang, 'tipe')->textInput([
                'class' => 'form-control tipe-field',
                'readonly' => true,
            ]) ?>

            <?= $form->field($modelBarang, 'warna')->textInput([
                'maxlength' => true,
                'id' => 'warna-field',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$dataSatuan = ArrayHelper::map(Unit::find()->asArray()->all(), 'unit_id', 'satuan');

// Create options HTML
$optionsHtml = '';
foreach ($dataSatuan as $unitId => $satuan) {
    $optionsHtml .= "<option value=\"{$unitId}\">{$satuan}</option>";
}

$js = <<<JS
    // Fungsi untuk menampilkan field "warna" dan header ketika tombol Consumable ditekan
    $('#toggle-consumable-button').on('click', function() {
        $('[id^="consumable-"]').show();  // Menampilkan semua field warna
        $('.warna-header').show();        // Menampilkan header kolom "warna"
        $('.warna-column').show();
        $('.tipe-field').val('Consumable');        // Menampilkan kolom "warna" di setiap baris
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        $('#toggle-non-consumable-button').removeClass('btn-secondary').addClass('btn-outline-secondary');
    });

    // Fungsi untuk menyembunyikan field "warna" dan header ketika tombol Non Consumable ditekan
    $('#toggle-non-consumable-button').on('click', function() {
        $('[id^="consumable-"]').hide();  // Menyembunyikan semua field warna
        $('.warna-header').hide();        // Menyembunyikan header kolom "warna"
        $('.warna-column').hide();        // Menyembunyikan kolom "warna" di setiap baris
        $('.tipe-field').val('Non Consumable'); 
        $('#toggle-consumable-button').removeClass('btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');
    });
    // Fungsi untuk mengatur tombol di setiap baris
    function updateRowButtons() {
        var rows = $('#barang-gridview table tbody tr');
        var rowCount = rows.length;

        rows.each(function(index) {
            var isLastRow = index === rowCount - 1;
            $(this).find('.add-row').toggle(isLastRow); // Tampilkan tombol tambah hanya di baris terakhir
            
            // Sembunyikan tombol hapus jika hanya ada satu baris
            if (rowCount === 1) {
                $(this).find('.delete-row').hide();
            } else {
                $(this).find('.delete-row').show(); // Tampilkan tombol hapus di semua baris kecuali jika hanya satu
            }
        });
    }

    // Panggil fungsi updateRowButtons saat halaman dimuat
    updateRowButtons();

    // Fungsi untuk menambahkan baris baru
    $(document).on('click', '.add-row', function(e) {
        e.preventDefault();
        // Ambil jumlah baris yang ada
        var index = $('#barang-gridview table tbody tr').length;
        var newRow = `<tr>
            <td class="serial-number">\${index + 1}</td>
            <td><input type="text" name="Barang[\${index}][kode_barang]" class="form-control" maxlength="true"></td>
            <td><input type="text" name="Barang[\${index}][nama_barang]" class="form-control" maxlength="true"></td>
            <td><input type="text" name="Barang[\${index}][angka]" class="form-control" maxlength="true"></td>
            <td>
                <select name="Barang[\${index}][unit_id]" class="form-control">
                    <option value="">Pilih Satuan</option>
                    $optionsHtml <!-- Use the options generated in PHP -->
                </select>
            </td>
            <td>
                <input type="text" name="Barang[\${index}][tipe]" class="form-control tipe-field" readonly>
            </td>
            <td class="warna-column"><input type="text" name="Barang[\${index}][warna]" class="form-control warna-field" id="consumable-\${index}" maxlength="true"></td>
            <td>
                <div class="d-flex justify-content-between align-content-center align-items-center">
                    <a href="#" class="btn btn-success btn-xs pb-1 px-2 add-row" title="Tambah Baris">
                        <i class="fas fa-plus"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-xs pb-1 px-2 delete-row" title="Hapus Baris">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </td>
        </tr>`;
        
        $('#barang-gridview table tbody').append(newRow);
        updateRowButtons(); // Perbarui tampilan tombol setelah menambah baris
    });

    // Fungsi untuk menghapus baris yang dipilih
    $(document).on('click', '.delete-row', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();

        // Update nomor urut pada kolom serial
        $('#barang-gridview table tbody tr').each(function(index) {
            $(this).find('.serial-number').text(index + 1);
        });
        updateRowButtons(); // Perbarui tampilan tombol setelah menghapus baris
    });
JS;
$this->registerJs($js);
?>

<style>
    .small-btn {
        padding: 2px 6px;
        font-size: 0.8em;
        margin-right: 2px;
    }

    /* Mengatur tata letak tombol secara horizontal */
    .action-buttons {
        display: flex;
        align-items: center;
        gap: 4px;
    }
</style>