<?php

namespace app\controllers;

use app\models\EoqRopHistory;
use app\models\EoqRopHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EoqRopHistoryController implements the CRUD actions for EoqRopHistory model.
 */
class EoqRopHistoryController extends Controller
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
     * Lists all EoqRopHistory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EoqRopHistorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EoqRopHistory model.
     * @param int $eoq_rop_history_id Eoq Rop History ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($eoq_rop_history_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($eoq_rop_history_id),
        ]);
    }

    /**
     * Creates a new EoqRopHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new EoqRopHistory();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'eoq_rop_history_id' => $model->eoq_rop_history_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EoqRopHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $eoq_rop_history_id Eoq Rop History ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($eoq_rop_history_id)
    {
        $model = $this->findModel($eoq_rop_history_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'eoq_rop_history_id' => $model->eoq_rop_history_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EoqRopHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $eoq_rop_history_id Eoq Rop History ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($eoq_rop_history_id)
    {
        $this->findModel($eoq_rop_history_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EoqRopHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $eoq_rop_history_id Eoq Rop History ID
     * @return EoqRopHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($eoq_rop_history_id)
    {
        if (($model = EoqRopHistory::findOne(['eoq_rop_history_id' => $eoq_rop_history_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
