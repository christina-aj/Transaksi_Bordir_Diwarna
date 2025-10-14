<?php

namespace app\controllers;

use app\models\EoqRop;
use app\models\EoqRopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EoqRopController implements the CRUD actions for EoqRop model.
 */
class EoqRopController extends Controller
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
     * Lists all EoqRop models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EoqRopSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EoqRop model.
     * @param int $EOQ_ROP_id Eoq Rop ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($EOQ_ROP_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($EOQ_ROP_id),
        ]);
    }

    /**
     * Creates a new EoqRop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new EoqRop();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'EOQ_ROP_id' => $model->EOQ_ROP_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EoqRop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $EOQ_ROP_id Eoq Rop ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($EOQ_ROP_id)
    {
        $model = $this->findModel($EOQ_ROP_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'EOQ_ROP_id' => $model->EOQ_ROP_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EoqRop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $EOQ_ROP_id Eoq Rop ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($EOQ_ROP_id)
    {
        $this->findModel($EOQ_ROP_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EoqRop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $EOQ_ROP_id Eoq Rop ID
     * @return EoqRop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($EOQ_ROP_id)
    {
        if (($model = EoqRop::findOne(['EOQ_ROP_id' => $EOQ_ROP_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
