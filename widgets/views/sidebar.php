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
$laproUrl = Url::to(['/laporan-produksi/index']);
$lapagreUrl = Url::to(['/laporan-agregat/index']);
$lapkelUrl = Url::to(['/laporan-keluar/index']);
$PembelianUrl = Url::to(['/pembelian/index']);
$InvoiceUrl = Url::to(['/pembelian-detail/index']);
$PenggunaanUrl = Url::to(['/penggunaan/index']);
$SuratJalanUrl = Url::to(['/surat-jalan/index']);
$GudangUrl = Url::to(['/gudang/index']);
$PemesananUrl = Url::to(['/pemesanan/index']);
$PesanDetailUrl = Url::to(['/pesan-detail/index']);
$PanduanUrl = Url::to(['/site/panduan']);
$PenggunaanUrl = Url::to(['/penggunaan/index']);
$SuratJalanUrl = Url::to(['/surat-jalan/index']);
$GudangUrl = Url::to(['/gudang/index']);
$PemesananUrl = Url::to(['/pemesanan/index']);
$JenisUrl = Url::to(['/jenis/index']);
$BarangProUrl = Url::to(['/barangproduksi/index']);
$NotaUrl = Url::to(['/nota/index']);



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
            <a href="<?= $dashboardUrl ?>" class="b-brand text-primary" style="margin-bottom:5px; margin-top:20px;">
                <img src="<?= Yii::getAlias('@web') ?>/assets/images/diwarna_logo.png" alt="logo image" class="logo-lg d-flex" style="width:90%">
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
                            <span class="pc-mtext">Karyawan</span>
                        </a>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-dolly-flatbed-alt"></i> </span><span class="pc-mtext">Pembelian</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $BarangUrl ?>">List Barang Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $PembelianUrl ?>">Riwayat Pembelian Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $InvoiceUrl ?>">Detail Pembelian Bahan Produksi</a></li>
                            <!-- <li class="pc-item"><a class="pc-link" href="">Report Barang</a></li> -->
                        </ul>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-box-alt"></i> </span><span class="pc-mtext">Produksi</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $UnitUrl ?>">Unit</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $PenggunaanUrl ?>">Penggunaan Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $StockUrl ?>">Stock Produksi</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-box-alt"></i> </span><span class="pc-mtext">Gudang</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $PemesananUrl ?>">Riwayat Pemesanan Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $PesanDetailUrl ?>">Detail Pemesanan Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $GudangUrl ?>">Stock Gudang</a></li>

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
                    <li class="pc-item">
                        <a href="<?= $ShifttUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-calendar-shift-swap"></i></span>
                            <span class="pc-mtext">Shift</span>
                        </a>
                    </li>


                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $laproUrl ?>">Laporan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $lapagreUrl ?>">Laporan Agregat</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $lapkelUrl ?>">Laporan Keluar</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $JenisUrl ?>">Jenis</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $BarangProUrl ?>">Barang Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $NotaUrl ?>">Nota</a></li>
                        </ul>
                    </li>

                    <li class="pc-item">
                        <a href="<?= $PanduanUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-book"></i></span>
                            <span class="pc-mtext">Panduan</span>
                        </a>
                    </li>
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
                                <i class="fi fi-ts-dolly-flatbed-alt"></i> </span><span class="pc-mtext">Pembelian</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $BarangUrl ?>">List Barang</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $PembelianUrl ?>">Riwayat Pembelian Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $InvoiceUrl ?>">Detail Pembelian Bahan Produksi</a></li>
                            <!-- <li class="pc-item"><a class="pc-link" href="">Report Barang</a></li> -->
                        </ul>
                    </li>
                    <li class="pc-item">
                        <a href="<?= $SupplierUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-supplier-alt"></i></i></span>
                            <span class="pc-mtext">Supplier</span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if ($roleName === 'Operator'): ?>
                    <li class="pc-item">
                        <a href="<?= $dashboardUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-box-alt"></i> </span><span class="pc-mtext">Gudang</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="<?= $PemesananUrl ?>">Riwayat Pemesanan</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $PesanDetailUrl ?>">Detail Pemesanan Barang</a></li>
                            <li class="pc-item"><a class="pc-link" href="<?= $GudangUrl ?>">Stock Gudang</a></li>

                        </ul>
                    </li>
                    <li class="pc-item">
                        <a href="<?= $MesinUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-conveyor-belt"></i></span>
                            <span class="pc-mtext">Mesin</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="<?= $ShifttUrl ?>" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-calendar-shift-swap"></i></span>
                            <span class="pc-mtext">Shift</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>