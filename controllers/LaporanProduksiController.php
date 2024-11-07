<?php

namespace app\controllers;

use app\models\LaporanProduksi;
use app\models\LaporanProduksisearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\Shift;

/**
 * LaporanProduksiController implements the CRUD actions for LaporanProduksi model.
 */
class LaporanProduksiController extends Controller
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
     * Lists all LaporanProduksi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LaporanProduksisearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LaporanProduksi model.
     * @param int $laporan_id Laporan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($laporan_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($laporan_id),
        ]);
    }

    /**
     * Creates a new LaporanProduksi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new LaporanProduksi();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'laporan_id' => $model->laporan_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LaporanProduksi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $laporan_id Laporan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($laporan_id)
    {
        $model = $this->findModel($laporan_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'laporan_id' => $model->laporan_id]);
        }
    
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LaporanProduksi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $laporan_id Laporan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($laporan_id)
    {
        $this->findModel($laporan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LaporanProduksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $laporan_id Laporan ID
     * @return LaporanProduksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($laporan_id)
    {
        if (($model = LaporanProduksi::findOne(['laporan_id' => $laporan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetShifts()

    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        if (Yii::$app->request->isPost) {
            $date = Yii::$app->request->post('date');
            $month = date('m', strtotime($date));

            // Ambil shift yang sesuai bulan
            $dataShift = Shift::find()
                ->where(['MONTH(tanggal)' => $month])
                ->asArray()
                ->all();

            $options = "<option value=''>Select Shift</option>";
            foreach ($dataShift as $shift) {
                $shiftTime = ($shift['shift'] == 1) ? 'Pagi' : 'Sore';
                $options .= "<option value='{$shift['shift_id']}'>{$shift['shift_id']} - {$shift['nama_operator']} ({$shiftTime})</option>";
            }

            return $options;
        }
        return "<option value=''>No shift available</option>";
    }



}
