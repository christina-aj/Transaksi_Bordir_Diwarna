<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" href="<?= Yii::getAlias('@web') ?>/assets/images/diwarna-logo-png.png" type="image/x-icon">
    <?php $this->head() ?>
</head>

<body data-pc-header="header-1" data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
    <?php $this->beginBody() ?>

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <div class="auth-main v1 bg-grd-primary">
        <div class="auth-wrapper">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'action' => ['site/login']
                        ]); ?>
                        <div class="text-center">
                            <img src="<?= Yii::getAlias('@web') ?>/assets/images/diwarna_logo.png" alt="images" class="img-fluid mb-4">
                            <h4 class="f-w-500 mb-3">Login</h4>
                        </div>
                        <!-- <div class="form-group mb-3">
                            <input type="text" class="form-control" id="floatingInput" placeholder="Username" required>
                        </div> -->
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                        <?= $form->field($model, 'password')->passwordInput() ?>
                        <?= $form->field($model, 'rememberMe')->checkbox([
                            'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                        ]) ?>
                        <div class="form-group">
                            <?= Html::a('Forgot Password?', ['site/request-password-reset'], ['class' => 'text-primary f-w-400 mb-0']) ?>
                        </div>
                        <!-- <div class="form-group mb-3">
                            <input type="password" class="form-control" id="floatingInput1" placeholder="Password" required>
                        </div> -->
                        <!-- <div class="d-flex mt-1 justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
                                <label class="form-check-label text-muted" for="customCheckc1">Remember me?</label>
                            </div>
                            <a href="../pages/forgot-password-v1.html">
                                <h6 class="f-w-400 mb-0">Forgot Password?</h6>
                            </a>
                        </div> -->
                        <div class="d-grid mt-4">
                            <?= Html::submitButton('Login', ['class' => ['btn btn-primary rounded'], 'name' => 'login-button']) ?>
                            <!-- <button type="button" class="btn btn-primary">Login</button> -->
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>