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
class LaporanAgregatController extends BaseController
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['delete', 'update', 'create', 'index', 'view'], // Aksi yang diatur
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'], // Hanya pengguna yang sudah login
                        ],
                    ],
                ]
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
        $day = Yii::$app->request->get('day');
        $nama_kerjaan = Yii::$app->request->get('nama_kerjaan');


        $startDate = Yii::$app->request->get('start_date');
        $endDate = Yii::$app->request->get('end_date');


        if ($startDate) {
            $startDate = \DateTime::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
        }
        if ($endDate) {
            $endDate = \DateTime::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
        }


        Yii::debug(compact('year', 'month', 'day', 'nama_kerjaan', 'startDate', 'endDate'), __METHOD__);

        if ($year || $month || $day || $nama_kerjaan || $startDate || $endDate) {
            $aggregatedData = LaporanAgregat::getFilterAggregatedData($year, $day, $month, $nama_kerjaan, $startDate, $endDate);
        } else {
            $aggregatedData = LaporanAgregat::getMonthlyAggregatedData();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'aggregatedData' => $aggregatedData,
        ]);
    }

    public function actionGetAggregatedData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            // Tambahkan logging
            Yii::info('Fetching aggregated data...');

            $query = (new \yii\db\Query())
                ->select([
                    'YEAR(tanggal_kerja) as year',
                    'SUM(kuantitas) as total_kuantitas'
                ])
                ->from('laporanproduksi')
                ->groupBy('YEAR(tanggal_kerja)')
                ->orderBy(['YEAR(tanggal_kerja)' => SORT_ASC]);

            $result = $query->all();

            // Log hasil query
            Yii::info('Query result: ' . print_r($result, true));

            if (empty($result)) {
                Yii::info('No data found');
                return [];
            }

            // Pastikan data terformat dengan benar
            $formattedResult = array_map(function ($row) {
                return [
                    'year' => (int)$row['year'],
                    'total_kuantitas' => (int)$row['total_kuantitas']
                ];
            }, $result);

            return $formattedResult;
        } catch (\Exception $e) {
            Yii::error('Error in getAggregatedData: ' . $e->getMessage());
            throw $e;
        }
    }
}
