<?php
namespace app\controllers;

use app\models\SupplierBarang;
use app\models\SupplierBarangSearch;
use Yii;
use app\models\SupplierBarangDetail;
use app\helpers\ModelHelper; 
use yii\web\Controller;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SupplierBarangController implements the CRUD actions for SupplierBarang model.
 */
class SupplierBarangController extends Controller
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
     * Lists all SupplierBarang models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierBarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupplierBarang model.
     * @param int $supplier_barang_id Supplier Barang ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($supplier_barang_id)
    {
        $model = $this->findModel($supplier_barang_id);
        $supplierBarangDetails = $model->supplierBarangDetails;

        return $this->render('view', [
            'model' => $model,
            'supplierBarangDetails' => $supplierBarangDetails,
        ]);
    }

    /**
     * Creates a new SupplierBarang model.
     * One-step form tapi tetap 2-step save
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SupplierBarang();
        
        // Set default 2 rows kosong
        $supplierBarangDetails = [
            new SupplierBarangDetail(),
            new SupplierBarangDetail(),
        ];

        if (Yii::$app->request->isPost) {
            // Step 1: Save parent dulu
            if ($model->load(Yii::$app->request->post())) {
                
                // ✅ VALIDASI: Cek apakah barang_id sudah ada
                $existingSupplierBarang = SupplierBarang::findOne(['barang_id' => $model->barang_id]);
                
                if ($existingSupplierBarang) {
                    $barang = \app\models\Barang::findOne($model->barang_id);
                    $namaBarang = $barang ? $barang->nama_barang : 'Barang ini';
                    
                    Yii::$app->session->setFlash('warning', 
                        "⚠️ {$namaBarang} sudah memiliki data supplier. Silakan UPDATE saja data yang sudah ada atau kembali ke awal"
                    );
                    
                    return $this->redirect(['update', 'supplier_barang_id' => $existingSupplierBarang->supplier_barang_id]);
                }
                
                $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
                $model->created_at = $now->format('Y-m-d H:i:s');
                $model->updated_at = $now->format('Y-m-d H:i:s');
                $model->total_supplier_barang = 0;

                if ($model->save()) {
                    // Simpan ID ke session
                    Yii::$app->session->set('temporarySupplierBarangId', $model->supplier_barang_id);
                    
                    // Step 2: Process details
                    return $this->processDetails($model->supplier_barang_id);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal menyimpan Supplier Barang: ' . json_encode($model->errors));
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'supplierBarangDetails' => $supplierBarangDetails,
        ]);
    }

    /**
     * Process supplier details (digunakan oleh create & update)
     * @param int $supplier_barang_id
     * @return \yii\web\Response
     */
    protected function processDetails($supplier_barang_id)
    {
        $model = SupplierBarang::findOne($supplier_barang_id);
        $detailData = Yii::$app->request->post('SupplierBarangDetail', []);

        $isValid = true;
        $newDetails = [];
        $supplierIds = []; // ✅ Untuk track supplier yang sudah ditambahkan

        // Filter & validasi
        foreach ($detailData as $index => $data) {
            if (empty($data['supplier_id'])) {
                continue;
            }

            // ✅ VALIDASI: Cek supplier duplikat
            if (in_array($data['supplier_id'], $supplierIds)) {
                $supplier = \app\models\Supplier::findOne($data['supplier_id']);
                $namaSupplier = $supplier ? $supplier->nama : 'Supplier ini';
                
                Yii::$app->session->setFlash('error', 
                    "❌ {$namaSupplier} tidak boleh ditambahkan 2 kali untuk barang yang sama! Duplicate!"
                );
                
                // Rollback: hapus supplier_barang yang baru dibuat
                if ($model) {
                    $model->delete();
                }
                Yii::$app->session->remove('temporarySupplierBarangId');
                
                return $this->redirect(['create']);
            }
            
            $supplierIds[] = $data['supplier_id'];

            $modelDetail = new SupplierBarangDetail();
            $modelDetail->supplier_barang_id = $supplier_barang_id;
            $modelDetail->supplier_id = $data['supplier_id'];
            $modelDetail->lead_time = $data['lead_time'] ?? null;
            $modelDetail->harga_per_kg = $data['harga_per_kg'] ?? null;
            $modelDetail->biaya_pesan = $data['biaya_pesan'] ?? null;
            $modelDetail->supp_utama = $data['supp_utama'] ?? 0;

            if ($modelDetail->validate()) {
                $newDetails[] = $modelDetail;
            } else {
                $isValid = false;
            }
        }

        // Pastikan hanya 1 utama
        $hasUtama = false;
        foreach ($newDetails as $detail) {
            if ($detail->supp_utama == 1) {
                if ($hasUtama) {
                    $detail->supp_utama = 0;
                } else {
                    $hasUtama = true;
                }
            }
        }

        if (!$hasUtama && count($newDetails) > 0) {
            $newDetails[0]->supp_utama = 1;
        }

        if ($isValid && count($newDetails) > 0) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->total_supplier_barang = count($newDetails);
                $model->updated_at = date('Y-m-d H:i:s');
                $model->save(false);

                foreach ($newDetails as $detail) {
                    $detail->save(false);
                }

                Yii::$app->session->remove('temporarySupplierBarangId');
                $transaction->commit();
                
                Yii::$app->session->setFlash('success', 'Supplier Barang berhasil disimpan.');
                return $this->redirect(['view', 'supplier_barang_id' => $supplier_barang_id]);

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', 'Validasi gagal atau tidak ada detail yang diisi.');
        }

        return $this->redirect(['index']);
    }



    /**
     * Updates an existing SupplierBarang model.
     * @param int $supplier_barang_id Supplier Barang ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($supplier_barang_id)
    {
        $model = $this->findModel($supplier_barang_id);
        $supplierBarangDetails = SupplierBarangDetail::findAll(['supplier_barang_id' => $supplier_barang_id]);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $detailData = Yii::$app->request->post('SupplierBarangDetail', []);
            $isValid = $model->validate();

            $existingDetailIds = [];
            foreach ($supplierBarangDetails as $detail) {
                $existingDetailIds[$detail->supplier_barang_detail_id] = $detail;
            }

            $updatedDetails = [];
            foreach ($detailData as $index => $data) {
                // Skip row kosong
                if (empty($data['supplier_id'])) {
                    continue;
                }

                $detailId = $data['supplier_barang_detail_id'] ?? null;

                if ($detailId && isset($existingDetailIds[$detailId])) {
                    // Update existing
                    $modelDetail = $existingDetailIds[$detailId];
                    $modelDetail->supplier_id = $data['supplier_id'];
                    $modelDetail->lead_time = $data['lead_time'] ?? null;
                    $modelDetail->harga_per_kg = $data['harga_per_kg'] ?? null;
                    $modelDetail->biaya_pesan = $data['biaya_pesan'] ?? null;
                    $modelDetail->supp_utama = $data['supp_utama'] ?? 0;
                    $isValid = $modelDetail->validate() && $isValid;
                    $updatedDetails[] = $modelDetail;
                } else {
                    // New detail
                    $newDetail = new SupplierBarangDetail();
                    $newDetail->supplier_barang_id = $supplier_barang_id;
                    $newDetail->supplier_id = $data['supplier_id'];
                    $newDetail->lead_time = $data['lead_time'] ?? null;
                    $newDetail->harga_per_kg = $data['harga_per_kg'] ?? null;
                    $newDetail->biaya_pesan = $data['biaya_pesan'] ?? null;
                    $newDetail->supp_utama = $data['supp_utama'] ?? 0;
                    $isValid = $newDetail->validate() && $isValid;
                    $updatedDetails[] = $newDetail;
                }
            }

            // Pastikan hanya 1 utama
            $hasUtama = false;
            foreach ($updatedDetails as $detail) {
                if ($detail->supp_utama == 1) {
                    if ($hasUtama) {
                        $detail->supp_utama = 0;
                    } else {
                        $hasUtama = true;
                    }
                }
            }

            if (!$hasUtama && count($updatedDetails) > 0) {
                $updatedDetails[0]->supp_utama = 1;
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Handle delete rows
                $deletedDetails = json_decode(Yii::$app->request->post('deleteRows', '[]'), true);
                if (is_array($deletedDetails)) {
                    foreach ($deletedDetails as $deletedId) {
                        $deletedDetail = SupplierBarangDetail::findOne($deletedId);
                        if ($deletedDetail) {
                            $deletedDetail->delete();
                        }
                    }
                }

                if ($isValid) {
                    $model->total_supplier_barang = count($updatedDetails);
                    $model->updated_at = date('Y-m-d H:i:s');
                    $model->save(false);

                    foreach ($updatedDetails as $detail) {
                        $detail->save(false);
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Supplier Barang berhasil diperbarui.');
                    return $this->redirect(['view', 'supplier_barang_id' => $model->supplier_barang_id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Validasi gagal. Periksa kembali data.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error("Update error: " . $e->getMessage());
                Yii::$app->session->setFlash('error', 'Gagal update: ' . $e->getMessage());
            }
        }

        return $this->render('_form', [
            'model' => $model,
            'supplierBarangDetails' => $supplierBarangDetails,
        ]);
    }

    /**
     * Cancel creation (delete temporary record)
     * @param int $supplier_barang_id
     * @return \yii\web\Response
     */
    public function actionCancel($supplier_barang_id)
    {
        $model = SupplierBarang::findOne($supplier_barang_id);

        if ($model) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                SupplierBarangDetail::deleteAll(['supplier_barang_id' => $supplier_barang_id]);
                $model->delete();

                $temporaryId = Yii::$app->session->get('temporarySupplierBarangId');
                if ($temporaryId == $supplier_barang_id) {
                    Yii::$app->session->remove('temporarySupplierBarangId');
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Supplier Barang telah dibatalkan.');
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal membatalkan: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->remove('temporarySupplierBarangId');
            Yii::$app->session->setFlash('error', 'Data tidak ditemukan.');
        }

        return $this->redirect(['index']);
        
    }

    /**
     * Deletes an existing SupplierBarang model.
     * @param int $supplier_barang_id Supplier Barang ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($supplier_barang_id)
    {
        $model = $this->findModel($supplier_barang_id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            SupplierBarangDetail::deleteAll(['supplier_barang_id' => $supplier_barang_id]);
            $model->delete();

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Supplier Barang berhasil dihapus.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the SupplierBarang model based on its primary key value.
     * @param int $supplier_barang_id Supplier Barang ID
     * @return SupplierBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($supplier_barang_id)
    {
        if (($model = SupplierBarang::findOne(['supplier_barang_id' => $supplier_barang_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}