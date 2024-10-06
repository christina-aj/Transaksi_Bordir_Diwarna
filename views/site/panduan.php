<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
$this->title = 'Panduan Web Inventaris';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">
    <div class="card table-card pb-5">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body mx-4">
            <p>
                Pada website ini terdapat beberapa icon di sidebar yang terdiri dari
            </p>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                        <p>
                            pada menu ini akan berisi informasi ringkasan, untuk sementara yang kami kepikiran yaitu
                        <ul>
                            <li>Stock Produksi</li>
                            <li>Stock Gudang</li>
                            <li>Peta client yang pernah pesan di diwarna </li>
                            <li>Recently Add </li>
                            <li>Statistic Agregat Produksi </li>
                            <li>Total Produksi </li>

                        </ul>

                        </p>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-user"></i></span>
                            <span class="pc-mtext">Karyawan</span>
                        </a>
                        <p>
                            pada menu ini merupakan menu yang hanya bisa diakses oleh SuperAdmin, menu ini memberikan informasi akun karyawan per divisinya.
                        </p>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-dolly-flatbed-alt"></i> </span><span class="pc-mtext">Pembelian</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>

                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#">List Barang Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="#">Riwayat Pembelian Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="#">Detail Pembelian Bahan Produksi</a></li>
                            <!-- <li class="pc-item"><a class="pc-link" href="">Report Barang</a></li> -->
                        </ul>
                        <p>
                            Pada menu pembelian merupakan menu validasi yang dimana akan dioperasikan oleh keuangan, dimana sebelum melakukan pembelian, semua kebutuhan dari pemesanan akan di validasi disini terlebih dahulu.
                            pada menu ini akan mengecek apakah harga dari sebuah barang produksi sama dengan harga yang sudah tercantum pada list barang.
                            Tujuan ini agar pada saat pembuatan laporan keuangan menjadi transparansi dan jelas.
                            pada table ini hanya bisa memuat edit dan view detail, karena untuk pembuatan datanya berawal dari menu gudang (Detil pemesanan bahan Produksi)
                        <ul>
                            <li>List Barang Produksi <p>
                                    pada menu ini merukan sebuah list dari barang yang didaftarkan (hanya daftar saja)
                                    Tujuan pembuatan ini agar jika pihak gudang ingin memesan bahan produksinya bisa langsung terhubung dan jika di pihak keuangan bisa langsung mengetahui dan memudahkan untuk validasi mengenai harga dari barang tersebut.
                                </p>
                            </li>
                            <li>Riwayat Pembelian Bahan Produksi<p>
                                    pada menu ini merupakan sebuah Log dari transaksi sebuah barang produksi yang berfungsi untuk kelancaran pencatatan keuangannya.
                                </p>
                            </li>
                            <li>Detail Pembelian Bahan Produksi<p>
                                    pada menu ini merupakan sebuah detil dari log transaksinya
                                </p>
                            </li>
                        </ul>
                        <h5>--Note-- Fitur pada menu ini masih dalam pengembangan sehingga tampilan tablenya masih berupa tampilan seperti table database saja. --Note-- </h5>
                        </p>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-box-alt"></i> </span><span class="pc-mtext">Produksi</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#">Unit</a></li>
                            <li class="pc-item"><a class="pc-link" href="#">Penggunaan Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="#">Stock Produksi</a></li>
                        </ul>
                        <p>
                            pada menu ini merupakan menu mengenai produksi dari stock produksi, penggunaan barang produksi, dan list dari unit
                        <ul>
                            <li>
                                Unit
                                <p>
                                    merupakan sebuah list dari satuan barang produksi
                                </p>
                            </li>
                            <li>
                                Penggunaan Bahan Produksi
                                <p>Pada menu ini merupakan sebuah menu yang berguna untuk mengalihkan barang dari stock gudang ke stock produksi. Dalam artian barang ini akan dipakai dalam sebuah produksi</p>
                            </li>
                            <li>
                                Stock Produksi
                                <p>
                                    Merupakan kartu stock yang berhubungan dengan produksi, stock ini akan mengambil dari stock gudang jika tidak memenuhi kondisi "langsung pakai".
                                    Jika memenuhi kondisi "langsung pakai" maka stock barang yang selesai dibeli akan masuk langsung ke Stock Produksi
                                </p>
                            </li>
                        </ul>
                        </p>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-box-alt"></i> </span><span class="pc-mtext">Gudang</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#">Riwayat Pemesanan Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="#">Detail Pemesanan Bahan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="#">Stock Gudang</a></li>

                        </ul>
                        <p>
                            Merupakan menu yang berhubungan dengan barang gudang, dimana barang yang selesai dibeli dan tidak langsung pakai maka datanya akan masuk di menu ini
                        <ul>
                            <li>
                                Riwayat Pemesanan Bahan Produksi
                                <p>
                                    Merupakan log dari pemesanan barang tidak tercantum keuangan hanya tercantum log pemesanan barang saja.
                                    pada menu ini data secara otomatis dibuat jika menekan tombol "create pesan detail" bisa di edit dan diliat secara detail.
                                    Validasi kelengkapan barang yang sudah datang juga dilakukan disini. pada menu ini juga untuk validasi barang per kode pemesanan, jadi bisa validasi banyak barang per kode pemesanan
                                </p>
                            </li>
                            <li>
                                Detail Pemesanan Bahan Produksi
                                <p>Merupakan menu untuk menampilkan detil dari log pemesanan tersebut dan untuk pembuatan sebuah pesanan barang produksi berada di menu ini.
                                    Validasi kelengkapan barang yang sudah datang juga dilakukan disini. pada menu ini juga untuk validasi barang tetapi hanya bisa di validasi per item saja
                                </p>
                            </li>
                            <li>
                                Stock Gudang
                                <p>
                                    Kartu stock yang berhubungan dengan penyimpanan barang. Barang akan tercatat secara otomatis disini setelah barang sudah tervalidasi
                                </p>
                            </li>
                        </ul>
                        </p>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-supplier-alt"></i></i></span>
                            <span class="pc-mtext">Supplier</span>
                        </a>
                        <p>Merupakan menu yang memuat data supplier</p>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-conveyor-belt"></i></span>
                            <span class="pc-mtext">Mesin</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-calendar-shift-swap"></i></span>
                            <span class="pc-mtext">Shift</span>
                        </a>
                    </li>


                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#">Laporan Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="#">Laporan Agregat</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>