<?php

namespace app\controllers;

use app\models\Pembelian;
use app\models\PembelianDetail;
use app\models\PembelianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;

/**
 * PembelianController implements the CRUD actions for Pembelian model.
 */
class PembelianController extends Controller
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
     * Lists all Pembelian models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PembelianSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pembelian model.
     * @param int $pembelian_id Pembelian ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($pembelian_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($pembelian_id),
        ]);
    }

    /**
     * Creates a new Pembelian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Pembelian();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'pembelian_id' => $model->pembelian_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pembelian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $pembelian_id Pembelian ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($pembelian_id)
    {
        $model = $this->findModel($pembelian_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'pembelian_id' => $model->pembelian_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pembelian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $pembelian_id Pembelian ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($pembelian_id)
    {
        $this->findModel($pembelian_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pembelian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $pembelian_id Pembelian ID
     * @return Pembelian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pembelian_id)
    {
        if (($model = Pembelian::findOne(['pembelian_id' => $pembelian_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCalculateTotalBiaya($pembelian_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Hitung total biaya dari semua detail pembelian
        $totalBiaya = PembelianDetail::find()
            ->where(['pembelian_id' => $pembelian_id])
            ->sum('total_biaya');

        // Kembalikan hasil sebagai JSON
        return ['total_biaya' => $totalBiaya];
    }

    public function actionGetUserInfo()
    {
        if (Yii::$app->user->isGuest) {
            return $this->asJson(['success' => false, 'message' => 'User not logged in']);
        }

        // Mengambil data user yang sedang login
        $user = Yii::$app->user->identity;

        return $this->asJson([
            'success' => true,
            'username' => $user->nama_pengguna,
        ]);
    }
}
