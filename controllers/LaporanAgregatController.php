<?php

namespace app\controllers;

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
        $aggregatedData = LaporanAgregat::getMonthlyAggregatedData();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'aggregatedData' => $aggregatedData,
        ]);
    }

    /**
     * Displays a single LaporanAgregat model.
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
     * Creates a new LaporanAgregat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new LaporanAgregat();

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
     * Updates an existing LaporanAgregat model.
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
     * Deletes an existing LaporanAgregat model.
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
     * Finds the LaporanAgregat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $laporan_id Laporan ID
     * @return LaporanAgregat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($laporan_id)
    {
        if (($model = LaporanAgregat::findOne(['laporan_id' => $laporan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
