<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\PermintaanPenjualan;
use app\models\PermintaanPenjualanSearch;
use app\models\PermintaanDetail;
use app\models\BarangProduksi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class PermintaanPenjualanController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel = new PermintaanPenjualanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($permintaan_penjualan_id)
    {
        $model = $this->findModel($permintaan_penjualan_id);
        
        return $this->render('view', [
            'model' => $model,
            'permintaanDetails' => $model->permintaanDetails, // Sesuaikan dengan nama variable di view.php
        ]);
    }

    public function actionCreate()
    {
        $modelPermintaan = new PermintaanPenjualan();
        $modelDetails = [new PermintaanDetail()];

        // Set default values
        $modelPermintaan->tanggal_permintaan = date('Y-m-d');
        $modelPermintaan->total_item_permintaan = 0;

        if ($modelPermintaan->load($this->request->post())) {
            $modelDetails = ModelHelper::createMultiple(PermintaanDetail::class);
            Model::loadMultiple($modelDetails, $this->request->post());
            
            $valid = $modelPermintaan->validate();
            $valid = Model::validateMultiple($modelDetails) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($modelPermintaan->save(false)) {
                        foreach ($modelDetails as $detail) {
                            $detail->permintaan_penjualan_id = $modelPermintaan->permintaan_penjualan_id;
                            if (!$detail->save(false)) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        
                        // Update total item
                        $modelPermintaan->total_item_permintaan = count($modelDetails);
                        $modelPermintaan->save(false);
                    }
                    
                    $transaction->commit();
                    return $this->redirect(['view', 'permintaan_penjualan_id' => $modelPermintaan->permintaan_penjualan_id]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('create', [
            'modelPermintaan' => $modelPermintaan,
            'modelDetails' => (empty($modelDetails)) ? [new PermintaanDetail] : $modelDetails,
        ]);
    }

    public function actionUpdate($permintaan_penjualan_id)
    {
        $modelPermintaan = $this->findModel($permintaan_penjualan_id);
        $modelDetails = $modelPermintaan->permintaanDetails;

        if ($modelPermintaan->load(Yii::$app->request->post())) {
            // Ambil id lama detail
            $oldIDs = ArrayHelper::map($modelDetails, 'permintaan_detail_id', 'permintaan_detail_id');
            
            $modelDetails = ModelHelper::createMultiple(PermintaanDetail::class, $modelDetails, 'permintaan_detail_id');
            Model::loadMultiple($modelDetails, Yii::$app->request->post());
            
            // Cari ID yang dihapus
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetails, 'permintaan_detail_id', 'permintaan_detail_id')));

            // Validasi master dan detail
            $valid = $modelPermintaan->validate();
            $valid = Model::validateMultiple($modelDetails) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($modelPermintaan->save(false)) {
                        // Hapus data detail yang dihapus
                        if (!empty($deletedIDs)) {
                            PermintaanDetail::deleteAll(['permintaan_detail_id' => $deletedIDs]);
                        }

                        // Simpan data detail
                        foreach ($modelDetails as $detail) {
                            $detail->permintaan_penjualan_id = $modelPermintaan->permintaan_penjualan_id;
                            if (!$detail->save(false)) {
                                $transaction->rollBack();
                                Yii::error($detail->errors);
                                break;
                            }
                        }

                        // Update total item
                        $modelPermintaan->total_item_permintaan = count($modelDetails);
                        $modelPermintaan->save(false);

                        $transaction->commit();
                        return $this->redirect(['view', 'permintaan_penjualan_id' => $modelPermintaan->permintaan_penjualan_id]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('_form', [
            'modelPermintaan' => $modelPermintaan,
            'modelDetails' => empty($modelDetails) ? [new PermintaanDetail()] : $modelDetails,
        ]);
    }

    public function actionDelete($permintaan_penjualan_id)
    {
        $this->findModel($permintaan_penjualan_id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSearch($q = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $query = BarangProduksi::find()
            ->select(['barang_produksi_id', 'nama', 'kode_barang_produksi']);
        
        if ($q) {
            $query->where(['like', 'nama', $q]);
        }
        
        $results = $query->limit(10)->asArray()->all();
        
        $output = [];
        foreach ($results as $item) {
            $output[] = [
                'barang_produksi_id' => $item['barang_produksi_id'],
                'nama_barang' => $item['nama'],
                'kode_barang' => $item['kode_barang_produksi'] ?? ''
            ];
        }
        
        return $output;
    }

    protected function findModel($permintaan_penjualan_id)
    {
        if (($modelPermintaan = PermintaanPenjualan::findOne(['permintaan_penjualan_id' => $permintaan_penjualan_id])) !== null) {
            return $modelPermintaan;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}