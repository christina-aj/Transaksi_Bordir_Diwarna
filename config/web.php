<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // Anda bisa menambahkan konfigurasi opsional di sini
        ],
    ],
    'defaultRoute' => 'site/login',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [

        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => [
                'httpOnly' => true,
            ],
            'name' => 'advance_session',
            'timeout' => 15, // 1 jam
        ],
        'request' => [
            'cookieValidationKey' => '2I2doEmMhGooS5mKkD9HxtR9tt5To-vK',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => true,
            'loginUrl' => ['site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error', // Tetap set ke action error
        ],
        'on beforeRequest' => function ($event) {
            $session = Yii::$app->session;
            if (!Yii::$app->request->isAjax && Yii::$app->errorHandler->exception === null) {
                $session->set('lastPage', Yii::$app->request->absoluteUrl); // Simpan halaman terakhir
            }
        },
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'], // Pastikan 'debug' ada
                    'logFile' => '@runtime/logs/app.log', // Tentukan file log
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'pembelian-detail/get-harga' => 'pembelian-detail/get-harga',
                'penggunaan/get-stock' => 'penggunaan/get-stock',
                'stock/get-stock' => 'stock/get-stock',
                'penggunaan/get-user-info' => 'penggunaan/get-user-info',
                'gudang/get-stock' => 'gudang/get-stock',
                'pemesanan/get-user-info' => 'pemesanan/get-user-info',
                'pemesanan/create-pemesanan' => 'pemesanan/create-pemesanan',
                'pesan-detail/search' => 'pesan-detail/search',
                'barang/search' => 'barang/search',
                'stock/search' => 'stock/search',
                'pesan-detail/update-multiple' => 'pesan-detail/update-multiple',
                'gudang/get-gudang' => 'gudang/get-gudang',
            ],
        ],



    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}



return $config;
