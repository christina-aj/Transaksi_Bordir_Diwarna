<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        // Daftar route yang bisa diakses tanpa perlu login
        $allowedActions = ['site/login', 'site/error'];
        $currentRoute = Yii::$app->controller->id . '/' . $action->id;

        // Jika route saat ini diperbolehkan, abaikan pengecekan
        if (in_array($currentRoute, $allowedActions)) {
            return parent::beforeAction($action);
        }

        // Periksa apakah sesi user sudah berakhir
        if (Yii::$app->user->isGuest || Yii::$app->user->identity === null) {
            Yii::info('User identity: ' . var_export(Yii::$app->user->identity, true), 'debug');

            Yii::$app->session->setFlash('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
            return $this->redirect(['site/login']);
        }

        return parent::beforeAction($action);
    }
}
