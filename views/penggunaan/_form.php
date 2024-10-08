<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Barang;
use app\models\Stock;
use app\models\User;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="penggunaan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal_digunakan')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'dd-mm-yyyy'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy',
        ],
    ]); ?>

    <!-- <?= $form->field($model, 'barang_id')->textInput() ?> -->
    <?php

    // Query untuk mengambil barang yang memiliki stock
    $availableBarang = Stock::find()
        ->select('barang_id')
        ->where(['>', 'quantity_akhir', 0]) // Hanya barang yang memiliki stock tersedia
        ->groupBy('barang_id') // Mengelompokkan berdasarkan barang_id untuk menghindari duplikasi
        ->all();

    // Ambil data barang berdasarkan barang_id yang memiliki stock
    $barangList = Barang::find()
        ->where(['barang_id' => ArrayHelper::getColumn($availableBarang, 'barang_id')])
        ->all();

    $dataPost = ArrayHelper::map($barangList, 'barang_id', function ($model) {
        return $model['barang_id'] . ' - ' . $model['kode_barang'] . ' - ' . $model['nama_barang'];
    });
    echo $form->field($model, 'barang_id')
        ->dropDownList(
            $dataPost,
            ['prompt' => 'Pilih Barang', 'barang_id' => 'nama_barang', 'id' => 'barang_id']
        );
    ?>

    <?= $form->field($model, 'stock')->textInput(['id' => 'stock', 'readonly' => true]) ?>


    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>


    <?php // Menampilkan user_id dan username di satu text field (readonly)
    // $user_info = Yii::$app->user->id . ' - ' . Yii::$app->user->identity->nama_pengguna;
    $users = User::find()->all();

    // Buat array untuk dropdown (key = id, value = username)
    $userList = ArrayHelper::map($users, 'id', function ($model) {
        return $model['user_id'] . ' - ' . $model['nama_pengguna']; // Menampilkan user_id dan username
    });

    // Buat dropdown list untuk memilih user
    echo $form->field($model, 'user_id', ['labelOptions' => ['label' => 'Pilih Karyawan']])->dropDownList(
        $userList,  // Data user untuk dropdown
        ['prompt' => 'Pilih User', 'id' => 'user_id']  // Placeholder dan opsi lain
    ); ?>



    <?= $form->field($model, 'jumlah_digunakan')->textInput() ?>
    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'tanggal_digunakan')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['penggunaan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $urlGetStock = Url::to(['penggunaan/get-stock']);
    $this->registerJs("
        $('#barang_id').change(function() {
            var barang_id = $(this).val();
            var url = '$urlGetStock';
            
            // Debug log untuk memastikan URL dan barang_id
            console.log('Request URL: ' + url);
            console.log('Barang ID: ' + barang_id);
            
            // Mengirimkan request AJAX ke controller
            $.post(url, { barang_id: barang_id }, function(data) {
                if (data.quantity_akhir !== null) {
                    // Jika quantity_akhir ada, masukkan ke field stock
                    $('#stock').val(data.quantity_akhir);
                } else {
                    // Jika tidak ada stock, kosongkan atau beri notifikasi
                    $('#stock').val('');
                    console.warn('Stock tidak ditemukan untuk barang_id ' + barang_id);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // Handle error pada request AJAX
                console.error('AJAX error: ', textStatus, errorThrown);
                console.log(jqXHR.responseText); // Debug response text
            });
        });
    ");
    ?>

    <?php
    $urlGetUsername = Url::to(['penggunaan/get-user-info']);
    $this->registerJs("
        $(document).ready(function() {
            // Mengirimkan request AJAX untuk mendapatkan informasi user yang sedang login
            $.ajax({
                url: '$urlGetUsername', // Sesuaikan dengan URL action controller
                type: 'GET',
                success: function(data) {
                    if (data.success) {
                        // Mengisi nama user dan email di form atau tempat yang diinginkan
                        $('#username').text(data.username);
                        $('#user-id').val(data.user_id); // Jika ingin menyimpan user_id di form
                    } else {
                        console.error('Gagal mendapatkan data user: ' + data.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error pada AJAX request: ' + textStatus + ' ' + errorThrown);
                }
            });
        });
    ");
    ?>

</div>