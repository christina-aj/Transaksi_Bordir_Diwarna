<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/images/favicon.svg',
        "assets/css/plugins/jsvectormap.min.css",
        "https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap",
        "assets/fonts/tabler-icons.min.css",
        "assets/fonts/feather.css",
        "assets/fonts/fontawesome.css",
        "assets/fonts/material.css",
        "assets/css/style.css",
        "assets/css/pagination.css",
        "assets/css/style-preset.css",
        'https://cdn-uicons.flaticon.com/2.5.1/uicons-thin-straight/css/uicons-thin-straight.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css',
        'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css'

    ];
    public $js = [
        'assets/js/plugins/apexcharts.min.js',
        'assets/js/plugins/jsvectormap.min.js',
        'assets/js/plugins/world.js',
        'assets/js/plugins/world-merc.js',
        'assets/js/pages/dashboard-sales.js',
        'assets/js/plugins/popper.min.js',
        'assets/js/plugins/simplebar.min.js',
        'assets/js/plugins/bootstrap.min.js',
        'assets/js/fonts/custom-font.js',
        'assets/js/pcoded.js',
        'assets/js/plugins/feather.min.js',
        'https://kit.fontawesome.com/445c1285d8.js',
        'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
