<?php

namespace app\controllers;

use app\models\DetailPermintaan;
use app\models\DetailPermintaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DetailPermintaanController implements the CRUD actions for DetailPermintaan model.
 */
class DetailPermintaanController extends Controller
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
     * Lists all DetailPermintaan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DetailPermintaanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DetailPermintaan model.
     * @param int $detail_permintaan_id Detail Permintaan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($detail_permintaan_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($detail_permintaan_id),
        ]);
    }

    /**
     * Creates a new DetailPermintaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new DetailPermintaan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'detail_permintaan_id' => $model->detail_permintaan_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DetailPermintaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $detail_permintaan_id Detail Permintaan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($detail_permintaan_id)
    {
        $model = $this->findModel($detail_permintaan_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'detail_permintaan_id' => $model->detail_permintaan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DetailPermintaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $detail_permintaan_id Detail Permintaan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($detail_permintaan_id)
    {
        $this->findModel($detail_permintaan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DetailPermintaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $detail_permintaan_id Detail Permintaan ID
     * @return DetailPermintaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($detail_permintaan_id)
    {
        if (($model = DetailPermintaan::findOne(['detail_permintaan_id' => $detail_permintaan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
