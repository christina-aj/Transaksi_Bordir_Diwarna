<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\RiwayatPenjualan;
use app\models\RiwayatPenjualanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\BarangProduksi;
use yii;
use yii\base\Model;


/**
 * RiwayatPenjualanController implements the CRUD actions for RiwayatPenjualan model.
 */
class RiwayatPenjualanController extends Controller
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
     * Lists all RiwayatPenjualan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RiwayatPenjualanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RiwayatPenjualan model.
     * @param int $riwayat_penjualan_id Riwayat Penjualan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($riwayat_penjualan_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($riwayat_penjualan_id),
        ]);
    }

    /**
     * Creates a new RiwayatPenjualan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        // $model = new RiwayatPenjualan();
        $modelRiwayatPenjualans = [new RiwayatPenjualan()];

        if ($this->request->isPost) {
            $modelRiwayatPenjualans = ModelHelper::createMultiple(RiwayatPenjualan::classname());
            Model::loadMultiple($modelRiwayatPenjualans, Yii::$app->request->post());

            foreach ($modelRiwayatPenjualans as $modelRiwayatPenjualan) {
                $modelRiwayatPenjualan->created_at = date('Y-m-d H:i:s');
            }
            if (Model::validateMultiple($modelRiwayatPenjualans)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelRiwayatPenjualans as $modelRiwayatPenjualan) {
                        if (!$modelRiwayatPenjualan->save(false)) {
                            throw new \Exception('Gagal menyimpan riwayat penjualan');
                        }
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Semua riwayat berhasil disimpan');
                    return $this->redirect(['index']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            } else {
                // Debug validation error
                foreach ($modelRiwayatPenjualans as $index => $modelRiwayatPenjualan) {
                    Yii::info("Item #$index errors: " . json_encode($modelRiwayatPenjualan->getErrors()), 'debug');
                }
            }
        }

        return $this->render('create', [
            'modelRiwayatPenjualans' => $modelRiwayatPenjualans,
            'isreadonly' => true,
        ]);
    }

    /**
     * Updates an existing RiwayatPenjualan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $riwayat_penjualan_id Riwayat Penjualan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($riwayat_penjualan_id)
    {
        $model = $this->findModel($riwayat_penjualan_id);
        
        // Load data dari relasi
        if ($model->barang_produksi_id) {
            $barangProduksi = BarangProduksi::findOne($model->barang_produksi_id);
            if ($barangProduksi) {
                $model->nama = $barangProduksi->nama;
                $model->kode_barang_produksi = $barangProduksi->kode_barang_produksi;
            }
        }

        if ($this->request->isPost) {
            $postData = $this->request->post('RiwayatPenjualan');
            
            // Ambil data dari index [0] karena dari GridView
            if (isset($postData[0])) {
                if ($model->load(['RiwayatPenjualan' => $postData[0]]) && $model->save()) {
                    Yii::$app->session->setFlash('success', 'Data berhasil diupdate');
                    // return $this->redirect(['view', 'riwayat_penjualan_id' => $model->riwayat_penjualan_id]);
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'models' => [$model],
        ]);
    }

    /**
     * Deletes an existing RiwayatPenjualan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $riwayat_penjualan_id Riwayat Penjualan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($riwayat_penjualan_id)
    {
        $this->findModel($riwayat_penjualan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Search barangproduksi untuk AJAX
     */
    public function actionSearch($query)
    {
        $data = BarangProduksi::find()
            ->select(['barangproduksi.barang_produksi_id', 'barangproduksi.kode_barang_produksi', 'barangproduksi.nama'])
            ->where(['like', 'barangproduksi.nama', $query])
            ->orWhere(['like', 'barangproduksi.kode_barang_produksi', $query])
            ->groupBy(['barangproduksi.barang_produksi_id', 'barangproduksi.kode_barang_produksi', 'barangproduksi.nama'])
            ->asArray()
            ->all();


        Yii::info("Data Search Result: " . json_encode($data), 'debug');
        return \yii\helpers\Json::encode($data);
    }

    /**
     * Finds the RiwayatPenjualan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $riwayat_penjualan_id Riwayat Penjualan ID
     * @return RiwayatPenjualan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($riwayat_penjualan_id)
    {
        if (($model = RiwayatPenjualan::findOne(['riwayat_penjualan_id' => $riwayat_penjualan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
