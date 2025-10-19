<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\SidebarWidget;
use yii\helpers\Url;
use yii\bootstrap5\Html;


AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Gradient Able is trending dashboard template made using Bootstrap 5 design framework. Gradient Able is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies.">
    <meta name="keywords" content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard">
    <meta name="author" content="codedthemes">
    <link rel="icon" href="<?= Yii::getAlias('@web') ?>/assets/images/diwarna-logo-png.png" type="image/x-icon">

    <?php $this->head() ?>
    <!-- [Meta] -->

</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <?= SidebarWidget::widget() ?>

    <!-- Header -->
    <header class="pc-header" style="background:linear-gradient(to right,  #73b4ff, #4099ff);">
        <div class="m-header">
            <a href="<?= Url::to(['site/index']) ?>" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="<?= Yii::getAlias('@web') ?>/assets/images/diwarna_logo.png" alt="logo image" class="logo-lg" style="width:65%">
            </a>
        </div>
        <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <!-- ======= Menu collapse Icon ===== -->
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-magnifying-glass"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
                                    <button class="btn btn-light-secondary btn-search">Search</button>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- [Mobile Media Block end] -->
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                            <img src="<?= Yii::getAlias('@web') ?>/assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-body">
                                <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                                    <ul class="list-group list-group-flush w-100">

                                        <li class="list-group-item">
                                            <div class="dropdown-item">
                                                <?=
                                                Html::beginForm(['/site/logout']) .
                                                    Html::submitButton('Logout (' . Yii::$app->user->identity->nama_pengguna . ')', ['class' => 'nav-link btn btn-link logout'])
                                                    . Html::endForm() ?>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pc-container" id="main">
        <!-- Flash Messages -->
        <?php
        $alertTypes = [
            'error' => 'danger',
            'success' => 'success',
            'warning' => 'warning',
            'info' => 'info'
        ];
        ?>

        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <?php $class = $alertTypes[$type] ?? $type; ?>
            <div class="alert alert-<?= $class ?> alert-dismissible fade show mt-3 mx-3" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
        <!-- End Flash Messages -->

        <?= $content ?>
    </main>


    <!-- [Body] end -->

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>