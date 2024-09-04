<?php

use yii\helpers\Url;
use yii\helpers\Html;

$roleName = Yii::$app->user->identity->roleName;

$dashboardUrl = Url::to(['site/index']);
$UserUrl = Url::to(['/user/index']);
$BarangUrl = Url::to(['/barang/index']);
$SupplierUrl = Url::to(['/supplier/index']);
$UnitUrl = Url::to(['/unit/index']);
$MesinUrl = Url::to(['/mesin/index']);
$ReportUrl = Url::to(['/report/index']);
$StockUrl = Url::to(['/stock/index']);
$ShifttUrl = Url::to(['/shift/index']);
$PembelianUrl = Url::to(['/pembelian/index']);
$InvoiceUrl = Url::to(['/pembelian-detail/index']);
$PenggunaanUrl = Url::to(['/penggunaan/index']);



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
                            <span class="pc-mtext">Akun</span>
                        </a>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-dolly-flatbed-alt"></i> </span><span class="pc-mtext">Pembelian</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $PembelianUrl ?>">Pembelian</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $UnitUrl ?>">Unit</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $InvoiceUrl ?>">Invoice</a></li>
                            <li class="pc-item"><a class="pc-link" href="">Report Barang</a></li>
                        </ul>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-box-alt"></i> </span><span class="pc-mtext">Stock</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $BarangUrl ?>">List Barang</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $PenggunaanUrl ?>">Penggunaan</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $StockUrl ?>">Report Stock</a></li>
                        </ul>
                    </li>

                    <!-- <li class="pc-item">
                        <a href="<?= $StockUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-box-alt"></i></i></span>
                            <span class="pc-mtext">Stock</span>
                        </a>
                    </li> -->

                    <li class="pc-item">
                        <a href="<?= $SupplierUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-supplier-alt"></i></i></span>
                            <span class="pc-mtext">Supplier</span>
                        </a>
                    </li>

                    <!-- <li class="pc-item">
                        <a href="<?= $UnitUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-ruler-vertical"></i></span>
                            <span class="pc-mtext">Unit</span>
                        </a>
                    </li> -->

                    <li class="pc-item">
                        <a href="<?= $MesinUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-conveyor-belt"></i></span>
                            <span class="pc-mtext">Mesin</span>
                        </a>
                    </li>


                    <!-- <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $StockUrl ?>">Stock</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $ShifttUrl ?>">Shift</a></li>
                        </ul>
                    </li> -->
                <?php endif; ?>

                <?php if ($roleName === 'Admin'): ?>
                    <li class="pc-item">
                        <a href="<?= $dashboardUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>


                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $StockUrl ?>">Stock</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $ShifttUrl ?>">Shift</a></li>
                        </ul>
                    </li>

                    <li class="pc-item">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i>
                            </span>
                            <span class="pc-mtext">Barang</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i>
                            </span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $BarangUrl ?>">List Barang</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $ShifttUrl ?>">Shift</a></li>
                        </ul>
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
                        </ul>
                    </li>
                <?php endif; ?>





                <!-- <li class="pc-item pc-caption">
                    <label>UI Components</label>
                    <i class="ph ph-compass-tool"></i>
                </li>
                <li class="pc-item">
                    <a href="../elements/bc_typography.html" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-text-aa"></i></span>
                        <span class="pc-mtext">Typography</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="../elements/bc_color.html" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-palette"></i></span>
                        <span class="pc-mtext">Color</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="../elements/icon-feather.html" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-flower-lotus"></i></span>
                        <span class="pc-mtext">Icons</span>
                    </a>
                </li>


                <li class="pc-item pc-caption">
                    <label>Pages</label>
                    <i class="ph ph-devices"></i>
                </li>
                <li class="pc-item">
                    <a href="<?= $loginUrl ?>" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-lock"></i></span>
                        <span class="pc-mtext">Login</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="../pages/register-v1.html" class="pc-link">
                        <span class="pc-micon"><i class="ph ph-user-circle-plus"></i></span>
                        <span class="pc-mtext">Register</span>
                    </a>
                </li>
                <li class="pc-item pc-caption">
                    <label>Other</label>
                    <i class="ph ph-suitcase"></i>
                </li> -->

                <!-- <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                            <i class="ph ph-tree-structure"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="#!">Stock</a></li>
                        <li class="pc-item"><a class="pc-link" href="#!">Shift</a></li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#!" class="pc-link">Level 2.2<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item"><a class="pc-link" href="#!">Level 3.1</a></li>
                                <li class="pc-item"><a class="pc-link" href="#!">Level 3.2</a></li>
                                <li class="pc-item pc-hasmenu">
                                    <a href="#!" class="pc-link">Level 3.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                                    <ul class="pc-submenu">
                                        <li class="pc-item"><a class="pc-link" href="#!">Level 4.1</a></li>
                                        <li class="pc-item"><a class="pc-link" href="#!">Level 4.2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#!" class="pc-link">Level 2.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item"><a class="pc-link" href="#!">Level 3.1</a></li>
                                <li class="pc-item"><a class="pc-link" href="#!">Level 3.2</a></li>
                                <li class="pc-item pc-hasmenu">
                                    <a href="#!" class="pc-link">Level 3.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                                    <ul class="pc-submenu">
                                        <li class="pc-item"><a class="pc-link" href="#!">Level 4.1</a></li>
                                        <li class="pc-item"><a class="pc-link" href="#!">Level 4.2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> -->
                <!-- <li class="pc-item"><a href="../other/sample-page.html" class="pc-link">
                        <span class="pc-micon">
                            <i class="ph ph-desktop"></i>
                        </span>
                        <span class="pc-mtext">Sample page

                        </span>
                    </a>
                </li> -->
                <!-- Navigation items here -->
            </ul>
        </div>
    </div>
</nav>