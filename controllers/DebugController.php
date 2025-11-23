<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class DebugController extends Controller
{
    public function actionClearCache()
    {
        Yii::$app->cache->flush();
        echo "Cache cleared!";
    }
}