<?php

namespace app\controllers;

use Yii;
use app\models\Penggunaan;
use app\models\PenggunaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PenggunaanController implements the CRUD actions for Penggunaan model.
 */
class PenggunaanController extends Controller
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
     * Lists all Penggunaan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PenggunaanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Penggunaan model.
     * @param int $penggunaan_id Penggunaan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($penggunaan_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($penggunaan_id),
        ]);
    }

    /**
     * Creates a new Penggunaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Penggunaan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'penggunaan_id' => $model->penggunaan_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Penggunaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $penggunaan_id Penggunaan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($penggunaan_id)
    {
        $model = $this->findModel($penggunaan_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'penggunaan_id' => $model->penggunaan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Penggunaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $penggunaan_id Penggunaan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($penggunaan_id)
    {
        $this->findModel($penggunaan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Penggunaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $penggunaan_id Penggunaan ID
     * @return Penggunaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($penggunaan_id)
    {
        if (($model = Penggunaan::findOne(['penggunaan_id' => $penggunaan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetStock()
    {
        // Mendapatkan data POST dari request
        $barang_id = Yii::$app->request->post('barang_id');

        // Cek apakah barang_id ada dalam request
        if ($barang_id) {
            // Mencari stock terbaru berdasarkan barang_id
            $stock = \app\models\Stock::find()
                ->where(['barang_id' => $barang_id])
                ->orderBy(['stock_id' => SORT_DESC]) // Mengambil stock terbaru
                ->one(); // Mengambil satu record terbaru

            // Jika ditemukan stock, kirimkan quantity_akhir sebagai respon JSON
            if ($stock) {
                return $this->asJson(['quantity_akhir' => $stock->quantity_akhir]);
            }
        }

        // Jika tidak ditemukan atau barang_id tidak valid, kembalikan null
        return $this->asJson(['quantity_akhir' => null]);
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
