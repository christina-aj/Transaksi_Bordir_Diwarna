<?php

namespace app\models;

use app\models\BomDetail;
use app\models\BomDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BomDetailController implements the CRUD actions for BomDetail model.
 */
class BomDetailController extends Controller
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
     * Lists all BomDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BomDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BomDetail model.
     * @param int $BOM_detail_id Bom Detail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($BOM_detail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($BOM_detail_id),
        ]);
    }

    /**
     * Creates a new BomDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BomDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'BOM_detail_id' => $model->BOM_detail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BomDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $BOM_detail_id Bom Detail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($BOM_detail_id)
    {
        $model = $this->findModel($BOM_detail_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BOM_detail_id' => $model->BOM_detail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BomDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $BOM_detail_id Bom Detail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($BOM_detail_id)
    {
        $this->findModel($BOM_detail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BomDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $BOM_detail_id Bom Detail ID
     * @return BomDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BOM_detail_id)
    {
        if (($model = BomDetail::findOne(['BOM_detail_id' => $BOM_detail_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
