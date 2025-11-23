<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-footer {
            background: #343a40;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .text-muted {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php $this->beginBody() ?>
    
    <div class="email-container">
        <div class="email-header">
            <h1>Sistem Inventory Diwarna</h1>
            <p>Notifikasi Otomatis Stock Menipis</p>
        </div>
        
        <div class="email-body">
            <?= $content ?>
        </div>
        
        <div class="email-footer">
            <p><strong>Email Otomatis dari Sistem Inventory</strong></p>
            <p>Tanggal: <?= date('d F Y, H:i:s') ?> WIB</p>
            <p class="text-muted" style="color: #adb5bd; margin-top: 10px;">
                Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
    
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>