<?php

namespace app\controllers;

use Yii;
use app\models\LaporanAgregat;
use app\models\LaporanAgregatsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LaporanAgregatController implements the CRUD actions for LaporanAgregat model.
 */
class LaporanAgregatController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    
    /**
     * Lists all LaporanAgregat models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LaporanAgregatsearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $year = Yii::$app->request->get('year');
        $month = Yii::$app->request->get('month');
        $nama_kerjaan = Yii::$app->request->get('nama_kerjaan');
        

        $startDate = Yii::$app->request->get('start_date');
        $endDate = Yii::$app->request->get('end_date');

        
        if ($startDate) {
            $startDate = \DateTime::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
        }
        if ($endDate) {
            $endDate = \DateTime::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
        }

      
        Yii::debug(compact('year', 'month', 'nama_kerjaan', 'startDate', 'endDate'), __METHOD__);

        if ($year || $month || $nama_kerjaan || $startDate || $endDate) {
            $aggregatedData = LaporanAgregat::getFilterAggregatedData($year, $month, $nama_kerjaan, $startDate, $endDate);
        } else {
            $aggregatedData = LaporanAgregat::getMonthlyAggregatedData();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'aggregatedData' => $aggregatedData,
        ]);
    }


}
