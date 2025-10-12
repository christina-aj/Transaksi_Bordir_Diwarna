<?php

namespace app\controllers;

use app\models\ForecastHistory;
use app\models\ForecastHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ForecastHistoryController implements the CRUD actions for ForecastHistory model.
 */
class ForecastHistoryController extends Controller
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
     * Lists all ForecastHistory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ForecastHistorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ForecastHistory model.
     * @param int $forecast_history_id Forecast History ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($forecast_history_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($forecast_history_id),
        ]);
    }

    /**
     * Creates a new ForecastHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ForecastHistory();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'forecast_history_id' => $model->forecast_history_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ForecastHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $forecast_history_id Forecast History ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($forecast_history_id)
    {
        $model = $this->findModel($forecast_history_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'forecast_history_id' => $model->forecast_history_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ForecastHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $forecast_history_id Forecast History ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($forecast_history_id)
    {
        $this->findModel($forecast_history_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ForecastHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $forecast_history_id Forecast History ID
     * @return ForecastHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($forecast_history_id)
    {
        if (($model = ForecastHistory::findOne(['forecast_history_id' => $forecast_history_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
