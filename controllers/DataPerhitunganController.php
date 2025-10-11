<?php

namespace app\controllers;

use app\models\DataPerhitungan;
use app\models\DataPerhitunganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DataPerhitunganController implements the CRUD actions for DataPerhitungan model.
 */
class DataPerhitunganController extends Controller
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
     * Lists all DataPerhitungan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DataPerhitunganSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataPerhitungan model.
     * @param int $data_perhitungan_id Data Perhitungan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($data_perhitungan_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($data_perhitungan_id),
        ]);
    }

    /**
     * Creates a new DataPerhitungan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new DataPerhitungan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'data_perhitungan_id' => $model->data_perhitungan_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DataPerhitungan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $data_perhitungan_id Data Perhitungan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($data_perhitungan_id)
    {
        $model = $this->findModel($data_perhitungan_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'data_perhitungan_id' => $model->data_perhitungan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DataPerhitungan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $data_perhitungan_id Data Perhitungan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($data_perhitungan_id)
    {
        $this->findModel($data_perhitungan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DataPerhitungan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $data_perhitungan_id Data Perhitungan ID
     * @return DataPerhitungan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($data_perhitungan_id)
    {
        if (($model = DataPerhitungan::findOne(['data_perhitungan_id' => $data_perhitungan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
