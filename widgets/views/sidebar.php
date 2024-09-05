<?php

use yii\helpers\Url;
use yii\helpers\Html;

$roleName = Yii::$app->user->identity->roleName;

$dashboardUrl = Url::to(['site/index']);
$UserUrl = Url::to(['/user/index']);
$BarangUrl = Url::to(['/item/index']);
$SupplierUrl = Url::to(['/supplier/index']);
$UnitUrl = Url::to(['/unit/index']);
$MesinUrl = Url::to(['/mesin/index']);
$ReportUrl = Url::to(['/report/index']);
$StockUrl = Url::to(['/report/stock']);
$ShifttUrl = Url::to(['/shift/index']);
$laproUrl = Url::to(['/laporan-produksi/index']);
$lapagreUrl = Url::to(['/laporan-agregat/index']);



$typographyUrl = Url::to(['site/typography']);
$colorUrl = Url::to(['site/color']);
$iconsUrl = Url::to(['site/icons']);
$loginUrl = Url::to(['site/login']);
$registerUrl = Url::to(['site/register']);
$samplePageUrl = Url::to(['site/sample-page']);
?>

<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="<?= $dashboardUrl ?>" class="b-brand text-primary">
                <img src="<?= Yii::getAlias('@web') ?>/assets/images/logo-dark.svg" alt="logo image" class="logo-lg">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>
                <?php if ($roleName === 'Super Admin'): ?>
                    <li class="pc-item">
                        <a href="<?= $dashboardUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $UserUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-user"></i></span>
                            <span class="pc-mtext">User</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $BarangUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-box-alt"></i></span>
                            <span class="pc-mtext">Barang</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $SupplierUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-supplier-alt"></i></i></span>
                            <span class="pc-mtext">Supplier</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $UnitUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-ruler-vertical"></i></span>
                            <span class="pc-mtext">Unit</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $MesinUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-conveyor-belt"></i></span>
                            <span class="pc-mtext">Mesin</span>
                        </a>
                    </li>


                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $StockUrl ?>">Stock</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $ShifttUrl ?>">Shift</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $laproUrl ?>">Laporan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $lapagreUrl ?>">Laporan Agregat</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($roleName === 'Admin'): ?>
                    <li class="pc-item">
                        <a href="<?= $dashboardUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $BarangUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-box-alt"></i></span>
                            <span class="pc-mtext">Barang</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $SupplierUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-supplier-alt"></i></i></span>
                            <span class="pc-mtext">Supplier</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $UnitUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-ruler-vertical"></i></span>
                            <span class="pc-mtext">Unit</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $MesinUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-conveyor-belt"></i></span>
                            <span class="pc-mtext">Mesin</span>
                        </a>
                    </li>


                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $StockUrl ?>">Stock</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $ShifttUrl ?>">Shift</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $laproUrl ?>">Laporan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $lapagreUrl ?>">Laporan Agregat</a></li>
                        </ul>
                    </li>
                <?php endif; ?>


                <?php if ($roleName === 'Operator'): ?>
                    <li class="pc-item">
                        <a href="<?= $dashboardUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $MesinUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-conveyor-belt"></i></span>
                            <span class="pc-mtext">Mesin</span>
                        </a>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $ShifttUrl ?>">Shift</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $laproUrl ?>">Laporan Produksi</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>