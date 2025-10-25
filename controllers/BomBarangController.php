<?php

namespace app\controllers;

use app\models\Barang;
use app\models\BarangProduksi;
use app\models\BomBarang;
use app\models\BomBarangSearch;
use app\models\BomDetail;
use app\helpers\ModelHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\base\Model;

/**
 * BomBarangController implements the CRUD actions for BomBarang model.
 */
class BomBarangController extends Controller
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
     * Lists all BomBarang models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BomBarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BomBarang model.
     * @param int $BOM_barang_id Bom Barang ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($BOM_barang_id)
    {
        $model = $this->findModel($BOM_barang_id);
        $bomDetails = $model->bomDetails;

        return $this->render('view', [
            'model' => $model,
            'bomDetails' => $bomDetails,
        ]);
    }

    /**
     * Creates a new BomBarang model.
     * Similar to Penggunaan create flow
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $modelBom = new BomBarang();

        // Set default data
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $modelBom->created_at = $now->format('Y-m-d H:i:s');
        $modelBom->updated_at = $now->format('Y-m-d H:i:s');
        $modelBom->total_bahan_baku = 0;

        // Simpan data dan langsung redirect ke add-details
        if ($modelBom->save()) {
            // Set session untuk ID BOM seperti pada penggunaan
            Yii::$app->session->set('temporaryBomId', $modelBom->BOM_barang_id);
            
            Yii::$app->session->setFlash('success', 'BOM Barang berhasil dibuat. Silakan tambahkan detail bahan baku.');
            return $this->redirect(['add-details', 'BOM_barang_id' => $modelBom->BOM_barang_id]);
        } else {
            Yii::$app->session->setFlash('error', 'Gagal membuat BOM Barang.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Add details to BOM Barang
     * @param int $BOM_barang_id
     * @return string|\yii\web\Response
     */
    public function actionAddDetails($BOM_barang_id)
    {
        // Ambil ID BOM dari session untuk validasi
        $temporaryId = Yii::$app->session->get('temporaryBomId');

        if (!$temporaryId || $temporaryId != $BOM_barang_id) {
            Yii::$app->session->setFlash('error', 'Session BOM tidak valid.');
            return $this->redirect(['index']);
        }

        $modelBom = BomBarang::findOne($BOM_barang_id);
        if (!$modelBom) {
            throw new NotFoundHttpException("Data BOM Barang tidak ditemukan.");
        }

        $modelDetails = [new BomDetail()];

        if (Yii::$app->request->isPost) {
            // Ambil barang_produksi_id dari POST
            $barangProduksiId = Yii::$app->request->post('BomBarang')['barang_produksi_id'] ?? null;
            
            if (empty($barangProduksiId)) {
                Yii::$app->session->setFlash('error', 'Barang Produksi harus dipilih.');
                return $this->render('create', [
                    'modelBom' => $modelBom,
                    'modelDetails' => $modelDetails,
                ]);
            }

            // Load detail data
            $detailData = Yii::$app->request->post('BomDetail', []);

            // Yii::info("==== DEBUG POST BomDetail ====", __METHOD__);
            // Yii::info(print_r($detailData, true), __METHOD__);

            $isValid = true;
            $newDetails = [];

            // Validasi setiap detail
            foreach ($detailData as $index => $data) {
                // Yii::info("Processing detail index $index", __METHOD__);
                // Yii::info(print_r($data, true), __METHOD__);

                $modelDetail = new BomDetail();
                $modelDetail->BOM_barang_id = $BOM_barang_id;
                $modelDetail->barang_id = $data['barang_id'] ?? null;
                $modelDetail->qty_BOM = $data['qty_BOM'] ?? null;
                $modelDetail->catatan = $data['catatan'] ?? '';

                if ($modelDetail->validate()) {
                    $newDetails[] = $modelDetail;
                } else {
                    $isValid = false;
                }
            }

            if ($isValid && count($newDetails) > 0) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Update barang_produksi_id di BOM
                    $modelBom->barang_produksi_id = $barangProduksiId;
                    $modelBom->total_bahan_baku = count($newDetails);
                    $modelBom->updated_at = date('Y-m-d H:i:s');

                    // var_dump($modelBom->attributes);
                    // var_dump($modelBom->validate());
                    // var_dump($modelBom->getErrors());
                    // die();
                    
                    if (!$modelBom->save(false)) {
                        throw new \Exception("Gagal update BOM Barang");
                    }

                    // Simpan detail bahan baku
                    foreach ($newDetails as $modelDetail) {
                        if (!$modelDetail->save(false)) {
                            throw new \Exception("Gagal menyimpan detail bahan baku: " . json_encode($modelDetail->getErrors()));
                        }
                    }

                    // Clear session setelah berhasil
                    Yii::$app->session->remove('temporaryBomId');

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'BOM Barang berhasil disimpan lengkap dengan detail bahan baku.');
                    return $this->redirect(['view', 'BOM_barang_id' => $BOM_barang_id]);

                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Validasi gagal. Periksa kembali data yang dimasukkan.');
            }
        }

        return $this->render('create', [
            'modelBom' => $modelBom,
            'modelDetails' => $modelDetails,
        ]);
    }

    /**
     * Updates an existing BomBarang model.
     * @param int $BOM_barang_id Bom Barang ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($BOM_barang_id)
    {
        $modelBom = BomBarang::findOne($BOM_barang_id);
        if (!$modelBom) {
            throw new NotFoundHttpException("Data BOM Barang tidak ditemukan.");
        }

        // Ambil detail BOM yang terkait
        $modelDetails = BomDetail::findAll(['BOM_barang_id' => $modelBom->BOM_barang_id]);

        // Set nama_barang dan kode_barang untuk setiap modelDetail
        foreach ($modelDetails as $modelDetail) {
            if ($modelDetail->barang) {
                $modelDetail->nama_barang = $modelDetail->barang->nama_barang;
                $modelDetail->kode_barang = $modelDetail->barang->kode_barang;
            }
        }

        if (Yii::$app->request->isPost) {
            $modelBom->load(Yii::$app->request->post());

            // Ambil data detail dari POST
            $detailData = Yii::$app->request->post('BomDetail', []);
            $isValid = $modelBom->validate();

            // Untuk tracking detail yang ada
            $existingDetailIds = [];
            foreach ($modelDetails as $detail) {
                $existingDetailIds[$detail->BOM_detail_id] = $detail;
            }

            // Loop untuk update detail yang ada dan tambah yang baru
            $updatedDetails = [];
            foreach ($detailData as $index => $data) {
                $detailId = $data['BOM_detail_id'] ?? null;
                
                if ($detailId && isset($existingDetailIds[$detailId])) {
                    // Update existing detail
                    $modelDetail = $existingDetailIds[$detailId];
                    $modelDetail->barang_id = $data['barang_id'] ?? $modelDetail->barang_id;
                    $modelDetail->qty_BOM = $data['qty_BOM'] ?? $modelDetail->qty_BOM;
                    $modelDetail->catatan = $data['catatan'] ?? '';
                    $isValid = $modelDetail->validate() && $isValid;
                    $updatedDetails[] = $modelDetail;
                } else {
                    // New detail
                    $newDetail = new BomDetail();
                    $newDetail->BOM_barang_id = $modelBom->BOM_barang_id;
                    $newDetail->barang_id = $data['barang_id'] ?? null;
                    $newDetail->qty_BOM = $data['qty_BOM'] ?? null;
                    $newDetail->catatan = $data['catatan'] ?? '';
                    $isValid = $newDetail->validate() && $isValid;
                    $updatedDetails[] = $newDetail;
                }
            }

            // Hapus BomDetail yang dihapus oleh user
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $deletedDetails = json_decode(Yii::$app->request->post('deleteRows', '[]'), true);
                if (is_array($deletedDetails)) {
                    foreach ($deletedDetails as $deletedId) {
                        $deletedDetail = BomDetail::findOne($deletedId);
                        if ($deletedDetail) {
                            $deletedDetail->delete();
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error("Gagal menghapus data: " . $e->getMessage());
            }

            // Jika valid, simpan perubahan
            if ($isValid) {
                $modelBom->total_bahan_baku = count($updatedDetails);
                $modelBom->updated_at = date('Y-m-d H:i:s');
                $modelBom->save(false);

                // Simpan semua detail
                foreach ($updatedDetails as $detail) {
                    $detail->save(false);
                }

                Yii::$app->session->setFlash('success', 'BOM Barang berhasil diperbarui.');
                return $this->redirect(['view', 'BOM_barang_id' => $modelBom->BOM_barang_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui data BOM. Harap periksa kembali data yang dimasukkan.');
            }
        }

        return $this->render('update', [
            'modelBom' => $modelBom,
            'modelDetails' => $modelDetails,
        ]);
    }

    /**
     * Cancel BOM (hapus jika masih temporary)
     * @param int $BOM_barang_id
     * @return \yii\web\Response
     */
    public function actionCancel($BOM_barang_id)
    {
        $modelBom = BomBarang::findOne($BOM_barang_id);

        if ($modelBom) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                BomDetail::deleteAll(['BOM_barang_id' => $BOM_barang_id]);
                $modelBom->delete();
                
                // Clear session jika ini temporary record
                $temporaryId = Yii::$app->session->get('temporaryBomId');
                if ($temporaryId == $BOM_barang_id) {
                    Yii::$app->session->remove('temporaryBomId');
                }
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'BOM Barang telah dibatalkan.');
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal membatalkan BOM: ' . $e->getMessage());
            }
        } else {
            // Tetap clear session meskipun record tidak ditemukan
            Yii::$app->session->remove('temporaryBomId');
            Yii::$app->session->setFlash('error', 'Data BOM tidak ditemukan.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Cleanup session
     * @return \yii\web\Response
     */
    public function actionCleanupSession()
    {
        Yii::$app->session->remove('temporaryBomId');
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing BomBarang model.
     * @param int $BOM_barang_id Bom Barang ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($BOM_barang_id)
    {
        $model = $this->findModel($BOM_barang_id);
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Hapus detail terlebih dahulu
            BomDetail::deleteAll(['BOM_barang_id' => $BOM_barang_id]);
            
            // Hapus BOM
            $model->delete();
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'BOM Barang berhasil dihapus.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Gagal menghapus BOM: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the BomBarang model based on its primary key value.
     * @param int $BOM_barang_id Bom Barang ID
     * @return BomBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BOM_barang_id)
    {
        if (($model = BomBarang::findOne(['BOM_barang_id' => $BOM_barang_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // /**
    // * Search action untuk typeahead autocomplete
    // * Digunakan untuk mencari barang di berbagai form (BOM, Penggunaan, dll)
    // * 
    // * @param string|null $q Query string untuk pencarian
    // * @param bool $is_search_form Flag untuk format output berbeda
    // * @return array JSON response dengan data barang
    // */
    // public function actionSearch($q = null, $is_search_form = false)
    // {
    //     // Set response format ke JSON
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     try {
    //         // Log query untuk debugging
    //         \Yii::info("Barang Search called with q: " . $q, __METHOD__);

    //         // Validasi query parameter
    //         if (empty($q)) {
    //             \Yii::warning("Query parameter is missing in Barang search.");
    //             return [
    //                 [
    //                     'barang_id' => null,
    //                     'kode_barang' => null,
    //                     'nama_barang' => 'Ketik untuk mencari...',
    //                     'value' => 'Ketik untuk mencari...'
    //                 ]
    //             ];
    //         }

    //         // Query barang dari database dengan join ke unit
    //         $items = \app\models\Barang::find()
    //             ->select(['barang.barang_id', 'barang.kode_barang', 'barang.nama_barang', 'barang.angka', 'barang.warna', 'unit.satuan'])
    //             ->leftJoin('unit', 'barang.unit_id = unit.unit_id')
    //             ->where(['like', 'barang.nama_barang', $q])
    //             ->orWhere(['like', 'barang.kode_barang', $q])
    //             ->orWhere(['like', 'barang.angka', $q])
    //             ->orWhere(['like', 'barang.warna', $q])
    //             ->limit(15)
    //             ->asArray()
    //             ->all();

    //         // Log hasil query untuk debugging
    //         \Yii::debug("Query result count: " . count($items), __METHOD__);

    //         // Jika tidak ada hasil
    //         if (empty($items)) {
    //             return [
    //                 [
    //                     'barang_id' => null,
    //                     'kode_barang' => null,
    //                     'nama_barang' => 'Barang tidak ditemukan',
    //                     'angka' => null,
    //                     'satuan' => null,
    //                     'warna' => null,
    //                     'value' => 'Barang tidak ditemukan'
    //                 ]
    //             ];
    //         }

    //         // Format hasil untuk typeahead
    //         $result = [];
    //         foreach ($items as $item) {
    //             // Buat display value yang informatif
    //             $displayValue = $item['kode_barang'] . ' - ' . $item['nama_barang'];
    //             if (!empty($item['angka'])) {
    //                 $displayValue .= ' (' . $item['angka'] . ')';
    //             }
    //             if (!empty($item['warna'])) {
    //                 $displayValue .= ' - ' . $item['warna'];
    //             }

    //             $result[] = [
    //                 'barang_id' => $item['barang_id'],
    //                 'kode_barang' => $item['kode_barang'],
    //                 'nama_barang' => $item['nama_barang'],
    //                 'angka' => $item['angka'] ?? '',
    //                 'satuan' => $item['satuan'] ?? '',
    //                 'warna' => $item['warna'] ?? '',
    //                 // Value untuk ditampilkan di typeahead
    //                 'value' => $is_search_form ? $item['nama_barang'] : $displayValue
    //             ];
    //         }

    //         return $result;

    //     } catch (\Exception $e) {
    //         \Yii::error("Error in Barang search: " . $e->getMessage(), __METHOD__);
    //         return [
    //             [
    //                 'barang_id' => null,
    //                 'kode_barang' => null,
    //                 'nama_barang' => 'Error: ' . $e->getMessage(),
    //                 'value' => 'Terjadi kesalahan'
    //             ]
    //         ];
    //     }
    // }

    // /**
    //  * Get barang by ID untuk AJAX request
    //  * @param int $id
    //  * @return array
    //  */
    // public function actionGetBarang($id)
    // {
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
    //     try {
    //         $barang = \app\models\Barang::find()
    //             ->select(['barang.barang_id', 'barang.kode_barang', 'barang.nama_barang', 'barang.angka', 'barang.warna', 'unit.satuan'])
    //             ->leftJoin('unit', 'barang.unit_id = unit.unit_id')
    //             ->where(['barang.barang_id' => $id])
    //             ->asArray()
    //             ->one();
            
    //         if ($barang) {
    //             return [
    //                 'success' => true,
    //                 'data' => $barang
    //             ];
    //         } else {
    //             return [
    //                 'success' => false,
    //                 'message' => 'Barang tidak ditemukan'
    //             ];
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ];
    //     }
    // }

    // /**
    //  * Test action untuk memastikan search berfungsi
    //  * @return array
    //  */
    // public function actionTestSearch()
    // {
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
    //     try {
    //         $count = \app\models\Barang::find()->count();
    //         $sample = \app\models\Barang::find()
    //             ->select(['barang_id', 'kode_barang', 'nama_barang'])
    //             ->limit(5)
    //             ->asArray()
    //             ->all();
            
    //         return [
    //             'status' => 'OK', 
    //             'total_barang' => $count,
    //             'sample_data' => $sample,
    //             'message' => 'Search action is working properly'
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             'status' => 'ERROR',
    //             'error' => $e->getMessage()
    //         ];
    //     }
    // }
}