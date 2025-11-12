<?php

namespace app\controllers;

use app\models\PermintaanPelanggan;
use app\models\PermintaanPelangganSearch;
use app\models\PermintaanDetail;
use app\models\BarangCustomPelanggan;
use app\models\Barangproduksi;
use app\models\MasterPelanggan;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * PermintaanPelangganController implements the CRUD actions for PermintaanPelanggan model.
 */
class PermintaanPelangganController extends Controller
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
     * Lists all PermintaanPelanggan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PermintaanPelangganSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PermintaanPelanggan model.
     * @param int $permintaan_id Permintaan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($permintaan_id)
    {
        $model = $this->findModel($permintaan_id);
        $permintaanDetails = $model->permintaanDetails;
        
        return $this->render('view', [
            'model' => $model,
            'permintaanDetails' => $permintaanDetails,
        ]);
    }

    /**
     * Creates a new PermintaanPelanggan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PermintaanPelanggan();
        $pelangganList = MasterPelanggan::find()->all();
        
        // Generate preview kode
        $nextKodee = PermintaanPelanggan::find()->max('permintaan_id') + 1;
        $nextKode = 'PP-' . str_pad($nextKodee, 3, '0', STR_PAD_LEFT);

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                // Load data permintaan
                if ($model->load($this->request->post())) {
                    // Set tipe_pelanggan dari hidden input
                    $model->tipe_pelanggan = Yii::$app->request->post('tipe_pelanggan', 1);
                    
                    // Hitung total item
                    $detailData = Yii::$app->request->post('PermintaanDetail', []);
                    $model->total_item_permintaan = count($detailData);

                    $lastId = PermintaanPelanggan::find()->max('permintaan_id') ?? 0;
                    $model->kode_permintaan = 'PP-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
                    
                    // Save permintaan
                    if (!$model->save()) {
                        throw new \Exception('Gagal menyimpan permintaan: ' . json_encode($model->errors));
                    }
                    
                    // Save detail items
                    foreach ($detailData as $detail) {
                        $detailModel = new PermintaanDetail();
                        $detailModel->permintaan_id = $model->permintaan_id;
                        
                        // Set barang berdasarkan tipe
                        if ($model->tipe_pelanggan == 1) {
                            // Custom
                            $detailModel->barang_custom_pelanggan_id = $detail['barang_custom_pelanggan_id'] ?? null;
                            $detailModel->barang_produksi_id = null;
                        } else {
                            // Polosan Ready
                            $detailModel->barang_produksi_id = $detail['barang_produksi_id'] ?? null;
                            $detailModel->barang_custom_pelanggan_id = null;
                        }
                        
                        $detailModel->qty_permintaan = $detail['qty_permintaan'] ?? 0;
                        $detailModel->catatan = $detail['catatan'] ?? '';
                        
                        if (!$detailModel->save()) {
                            throw new \Exception('Gagal menyimpan detail: ' . json_encode($detailModel->errors));
                        }
                    }
                    
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Permintaan pelanggan berhasil disimpan.');
                    return $this->redirect(['view', 'permintaan_id' => $model->permintaan_id]);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal menyimpan: ' . $e->getMessage());
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'pelangganList' => $pelangganList,
            'nextKode' => $nextKode,
        ]);
    }



    public function actionFinalkanBulanLalu()
    {
        $bulanLalu = date('m', strtotime('-1 month'));
        $tahunLalu = date('Y', strtotime('-1 month'));
        $namaBulanLalu = date('F Y', strtotime('-1 month'));
        $kodeBulanLalu = date('Ym', strtotime('-1 month'));

        $permintaanList = \app\models\PermintaanPelanggan::find()
            ->where(['status_permintaan' => 2])
            ->andWhere(['MONTH(tanggal_permintaan)' => $bulanLalu, 'YEAR(tanggal_permintaan)' => $tahunLalu])
            ->all();

        if (empty($permintaanList)) {
            Yii::$app->session->setFlash('warning', "Tidak ada permintaan berstatus Complete di bulan $namaBulanLalu.");
            return $this->redirect(['index']);
        }

        foreach ($permintaanList as $permintaan) {
            foreach ($permintaan->permintaanDetails as $detail) {
                $riwayat = new \app\models\RiwayatPenjualan();
                $riwayat->bulan_periode = $kodeBulanLalu;
                $riwayat->qty_penjualan = $detail->qty_permintaan;
                $riwayat->created_at = date('Y-m-d H:i:s');

                // Pilih kolom ID yang relevan
                if (!empty($detail->barang_produksi_id)) {
                    $riwayat->barang_produksi_id = $detail->barang_produksi_id;
                } elseif (!empty($detail->barang_custom_pelanggan_id)) {
                    $riwayat->barang_custom_pelanggan_id = $detail->barang_custom_pelanggan_id;
                }

                $riwayat->save(false);
            }

            // Update status permintaan jadi archived
            $permintaan->status_permintaan = 3;
            $permintaan->save(false);
        }

        Yii::$app->session->setFlash('success', "Permintaan bulan $namaBulanLalu berhasil difinalkan ke Riwayat Penjualan!");
        return $this->redirect(['index']);
    }




    /**
     * Get barang data by pelanggan via AJAX
     */
    public function actionGetBarangByPelanggan()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $pelangganId = Yii::$app->request->get('pelanggan_id');
        $tipe = Yii::$app->request->get('tipe', 1); // 1=custom, 2=polosan
        
        $data = [];
        
        if ($tipe == 1) {
            // Custom - ambil barang custom pelanggan
            $barangCustom = BarangCustomPelanggan::find()
                ->where(['pelanggan_id' => $pelangganId])
                ->all();
            
            foreach ($barangCustom as $barang) {
                $data[$barang->barang_custom_pelanggan_id] = $barang->kode_barang_custom . ' - ' . $barang->nama_barang_custom;
            }
        } else {
            // Polosan Ready - ambil semua barang produksi
            $barangProduksi = Barangproduksi::find()->all();
            
            foreach ($barangProduksi as $barang) {
                $data[$barang->barang_produksi_id] = $barang->kode_barang_produksi . ' - ' . $barang->nama;
            }
        }
        
        return $data;
    }

    /**
     * Updates an existing PermintaanPelanggan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $permintaan_id Permintaan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($permintaan_id)
    {
        $model = $this->findModel($permintaan_id);
        $pelangganList = MasterPelanggan::find()->all();

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                if ($model->load($this->request->post())) {
                    // Update tipe_pelanggan
                    $model->tipe_pelanggan = Yii::$app->request->post('tipe_pelanggan', $model->tipe_pelanggan);
                    
                    // Hitung total item
                    $detailData = Yii::$app->request->post('PermintaanDetail', []);
                    $model->total_item_permintaan = count($detailData);
                    
                    if (!$model->save()) {
                        // throw new \Exception('Gagal update permintaan');
                        throw new \Exception('Gagal update permintaan: ' . json_encode($model->getErrors()));
                    }
                    
                    // Delete existing details
                    PermintaanDetail::deleteAll(['permintaan_id' => $model->permintaan_id]);
                    
                    // Save new details
                    foreach ($detailData as $detail) {
                        $detailModel = new PermintaanDetail();
                        $detailModel->permintaan_id = $model->permintaan_id;
                        
                        if ($model->tipe_pelanggan == 1) {
                            $detailModel->barang_custom_pelanggan_id = $detail['barang_custom_pelanggan_id'] ?? null;
                            $detailModel->barang_produksi_id = null;
                        } else {
                            $detailModel->barang_produksi_id = $detail['barang_produksi_id'] ?? null;
                            $detailModel->barang_custom_pelanggan_id = null;
                        }
                        
                        $detailModel->qty_permintaan = $detail['qty_permintaan'] ?? 0;
                        $detailModel->catatan = $detail['catatan'] ?? '';
                        
                        if (!$detailModel->save()) {
                            throw new \Exception('Gagal update detail');
                        }
                    }
                    
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Permintaan pelanggan berhasil diupdate.');
                    return $this->redirect(['view', 'permintaan_id' => $model->permintaan_id]);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal update: ' . $e->getMessage());
                
            }
        }

        return $this->render('update', [
            'model' => $model,
            'pelangganList' => $pelangganList,
            'detailModels' => $model->permintaanDetails,
        ]);
    }

    /**
     * Deletes an existing PermintaanPelanggan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $permintaan_id Permintaan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($permintaan_id)
    // {
    //     $this->findModel($permintaan_id)->delete();

    //     return $this->redirect(['index']);
    // }
    public function actionDelete($permintaan_id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Hapus dulu detail yang terkait
            PermintaanDetail::deleteAll(['permintaan_id' => $permintaan_id]);

            // Baru hapus parent-nya
            $this->findModel($permintaan_id)->delete();

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Data permintaan berhasil dihapus.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Gagal hapus: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the PermintaanPelanggan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $permintaan_id Permintaan ID
     * @return PermintaanPelanggan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($permintaan_id)
    {
        if (($model = PermintaanPelanggan::findOne(['permintaan_id' => $permintaan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}