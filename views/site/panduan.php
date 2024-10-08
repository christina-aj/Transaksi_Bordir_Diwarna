<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
$this->title = 'Panduan Web Inventaris';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body mx-4">
            <h5>Alur</h5>
            <p>

                untuk alur penggunaan web ini sebagai berikut : <br>
                1. Dimulai dari "Menu gudang->Detail Pemesanan Bahan produksi", disinilah pemesanan barang terjadi. <br>
                2. Data secara otomatis akan muncul di pihak keuangan yang berada pada "Menu pembelian -> Detail Pembelian Bahan Produksi", pada menu ini pihak keuangan akan mengecek apakah harga sudah sesuai dengan supplier<br>
                3. Jika sudah benar maka terjadilah transaksi dan lognya akan tersimpan di "Menu pembelian -> Riwayat Pembelian Bahan Produksi"<br>
                4. Setelah barang sudah sampai digudang, maka pihak gudang akan memvalidasi barang yang datang tersebut di "Menu gudang->Riwayat Pemesanan->Tombol mata->muncul rincian detail yang telah dipesan->update Pesanan" pada mode update ini form yang bisa disinya hanyalah qty terima dan status barang sesuai atau tidak. <br>
                5. Kondisi barang masuk stock terdiri dari 2 kondisi :
            <ul>
                1. Jika pada pemesanan detail tercentang kondisi langsung pakai, maka qty terima yang tercatat akan masuk ke stock produksi<br>
                Misalkan saja qty terima = 10 <br>
                Checkbox langsung pakai tercentang<br>
                Maka barang yang sudah diterima akan tercatat pada quantity masuk pada kartu stock produksi.<br><br>
                2. Jika pada pemesanan detail tidak tercentang kondisi langsung pakai, maka qty terima yang tercatat akan masuk ke stock gudang<br>
                Misalkan saja qty terima = 10 <br>
                Checkbox langsung pakai tidak tercentang<br>
                Maka barang yang sudah diterima akan tercatat pada quantity masuk pada kartu stock gudang.
            </ul>
            Pada website ini terdapat beberapa icon di sidebar yang terdiri dari <br>
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
                        <br>
                        <strong>--Note-- Data masih belom di implementasikan dalam dashboard, dan masih sekedar garis besanya saja. --Note--</strong>
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
                            <li>Riwayat Pembelian Bahan Produksi <strong>(Fungsi belum terimplementasi dan tersinkronisasi, masih dalam pengembangan. Hanya bisa melihat view table saja.)</strong>
                                <p>
                                    pada menu ini merupakan sebuah Log dari transaksi sebuah barang produksi yang berfungsi untuk kelancaran pencatatan keuangannya.
                                </p>
                            </li>
                            <li>Detail Pembelian Bahan Produksi <strong>(Fungsi belum terimplementasi dan tersinkronisasi, masih dalam pengembangan. Hanya bisa melihat view table saja.)</strong>
                                <p>
                                    pada menu ini merupakan sebuah detil dari log transaksinya
                                </p>
                            </li>
                        </ul>
                        <strong>--Note-- Fitur pada menu ini masih dalam pengembangan sehingga tampilan tablenya masih berupa tampilan seperti table database saja. --Note-- </strong>
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
                                Penggunaan Bahan Produksi <strong>(Fungsi belum terimplementasi dan tersinkronisasi, masih dalam pengembangan. Hanya bisa melihat view table saja.)</strong>
                                <p>Pada menu ini merupakan sebuah menu yang berguna untuk mengalihkan barang dari stock gudang ke stock produksi. Dalam artian barang ini akan dipakai dalam sebuah produksi</p>
                            </li>
                            <li>
                                Stock Produksi <strong>(Fungsi belum terimplementasi dan tersinkronisasi, masih dalam pengembangan. Hanya bisa melihat view table saja.)</strong>
                                <p>
                                    Merupakan kartu stock yang berhubungan dengan produksi, stock ini akan mengambil dari stock gudang jika tidak memenuhi kondisi "langsung pakai".
                                    Jika memenuhi kondisi "langsung pakai" maka stock barang yang selesai dibeli akan masuk langsung ke Stock Produksi
                                </p>
                            </li>
                        </ul>
                        <strong>--Note-- Fitur pada menu ini masih dalam pengembangan sehingga fungsi penggunaannya masih belum terlihat. --Note-- </strong>
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
                                Stock Gudang <strong>(Fungsi belum terimplementasi dan tersinkronisasi, masih dalam pengembangan. Hanya bisa melihat view table saja.)</strong>
                                <p>
                                    Kartu stock yang berhubungan dengan penyimpanan barang. Barang akan tercatat secara otomatis disini setelah barang sudah tervalidasi
                                </p>
                            </li>
                        </ul>
                        <strong>--Note-- Pada Menu ini hanya fitur Stock Gudang saja yang masih dalam pengembangan sehingga masih belum kelihatan jelas penggunaannya --Note-- </strong>
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
                        <p>Merupakan menu yang memuat data mesin data yang dimasukan seperti nama dan deskripsi mesin</p>
                    </li>
                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-calendar-shift-swap"></i></span>
                            <span class="pc-mtext">Shift</span>
                        </a>
                        <p>Merupakan menu yang memuat data shift dengan variabel waktu dan juga nama operator dll</p>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-supplier-alt"></i></i></span>
                            <span class="pc-mtext">Barang Produksi</span>
                        </a>
                        <p>Merupakan menu yang memuat data Barang yang sudah di produksi bukan barang mentah/ bahan mentah produksi</p>
                    </li>

                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="fi fi-ts-supplier-alt"></i></i></span>
                            <span class="pc-mtext">Jenis</span>
                        </a>
                        <p>Merupakan menu yang memuat data Jenis barang yang di buat untuk komplemen data barang produksi</p>
                    </li>



                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                                <i class="fi fi-ts-ballot-check"></i> </span><span class="pc-mtext">Report</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#">Laporan Produksi</a>
                                <p>Merupakan menu yang memuat data laporan produksi yang bertujuan untuk melaporkan barang apa dan milik siapa di produksi dan oleh siapa berdasarkan shift
                                    untuk isian dapat menggunakan tombol create.
                                </p>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="#">Laporan Agregat</a>
                                <p>Merupakan menu yang memuat kumpulan data dari laporan produksi dan di agregatkan menjadi satu dengan jangka waktu 1 bulan ada juga tombol untuk melakukan filter
                                    data dengan user memasukan bulan tahun dan nama kerjaan nya user dapat memfilter data yang ingin dilihat (fitur Print data menyusul)
                                </p>
                            </li>
                    </li>
                    <li class="pc-item"><a class="pc-link" href="#">Laporan Keluar</a>
                        <p>Merupakan menu yang dapat di gunakan untuk melakukan pengurangan stok barang jika terjadi hal yang tidak dinginkan atau semisal ada kesalahan kesalahan lain di luar barang
                            dikirim untuk di jual
                        </p>
                    </li>
                    </li>
                    <li class="pc-item"><a class="pc-link" href="#">Nota</a>
                        <p>Merupakan menu yang dapat di gunakan untuk membuat faktur penjualan di mana juga ada menu untuk melakukan print faktur tersebut (ada fitur dimana jika barang sudah masuk di bagian faktur maka
                            barang yang ada di stok akan otomatis berkurang fitur ini masih menyusul)
                        </p>
                    </li>
                </ul>
                </li>
                </ul>
            </div>
        </div>
    </div>
</div>