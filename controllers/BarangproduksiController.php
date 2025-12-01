<?php

namespace app\controllers;

use Yii;
use app\models\Barangproduksi;
use app\models\Barangproduksisearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BarangproduksiController implements the CRUD actions for Barangproduksi model.
 */
class BarangproduksiController extends BaseController
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['delete', 'update', 'create', 'index', 'view'], // Aksi yang diatur
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'], // Hanya pengguna yang sudah login
                        ],
                    ],
                ]
            ]
        );
    }

    /**
     * Lists all Barangproduksi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Barangproduksisearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Barangproduksi model.
     * @param int $barang_produksi_id Barang ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionView($barang_produksi_id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($barang_produksi_id),
    //     ]);
    // }

    public function actionView($barang_produksi_id)
    {
        $model = $this->findModel($barang_produksi_id);
        
        // Load relasi bomDetails dengan barang dan unit
        $model = Barangproduksi::find()
            ->where(['barang_produksi_id' => $barang_produksi_id])
            ->with(['bomDetails.barang'])
            ->one();
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Barangproduksi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    //lama
    // public function actionCreate()
    // {
    //     $model = new Barangproduksi();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             return $this->redirect(['view', 'barang_produksi_id' => $model->barang_produksi_id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    //new gabung bom
    public function actionCreate()
    {
        // Ambil list barang mentah untuk dropdown BOM (jenis_barang = 1)
        $barangList = \app\models\Barang::find()
            ->where(['jenis_barang' => 1])
            ->select(['CONCAT(kode_barang, " - ", nama_barang) as nama', 'barang_id', 'unit_id'])
            ->indexBy('barang_id')
            ->asArray()
            ->all();
        
            if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $transaction = \Yii::$app->db->beginTransaction();
            
            try {
                $success = true;
                $errors = [];

                // Loop setiap produk jadi yang diinput
                if (isset($post['produkjadi']) && is_array($post['produkjadi'])) {
                    foreach ($post['produkjadi'] as $index => $produkjadii) {
                        // Simpan barang jadi / produksi
                        $barangProduksi = new \app\models\BarangProduksi();
                        $barangProduksi->kode_barang_produksi = $produkjadii['kode_barang_produksi'] ?? '';
                        $barangProduksi->nama = $produkjadii['nama'] ?? '';
                        $barangProduksi->nama_jenis = $produkjadii['nama_jenis'] ?? '';
                        $barangProduksi->ukuran = $produkjadii['ukuran'] ?? '';
                        $barangProduksi->deskripsi = $produkjadii['deskripsi'] ?? '';
                        // $barangProduksi->created_at = time();
                        // $barangProduksi->updated_at = time();
                        
                        if (!$barangProduksi->save()) {
                            $errors[] = "Produk #" . ($index + 1) . ": " . json_encode($barangProduksi->errors);
                            $success = false;
                            continue;
                        }

                        // Simpan BOM Custom untuk produk ini
                        if (isset($produkjadii['bom']) && is_array($produkjadii['bom'])) {
                            foreach ($produkjadii['bom'] as $bomIndex => $bomData) {
                                if (empty($bomData['barang_id']) || empty($bomData['qty'])) {
                                    continue; // Skip jika kosong
                                }

                                $bomBarang = new \app\models\BomDetail();
                                $bomBarang->barang_produksi_id = $barangProduksi->barang_produksi_id;
                                $bomBarang->barang_id = $bomData['barang_id'];
                                $bomBarang->qty_BOM = $bomData['qty'];
                                $bomBarang->catatan = $bomData['catatan'] ?? '';
                                // $bomBarang->created_at = date('Y-m-d H:i:s');
                                // $bomBarang->updated_at = date('Y-m-d H:i:s');
                                
                                if (!$bomBarang->save()) {
                                    $errors[] = "BOM Produk #" . ($index + 1) . " Bahan #" . ($bomIndex + 1) . ": " . json_encode($bomBarang->errors);
                                    $success = false;
                                }
                            }
                        }
                    }
                }

                if ($success && empty($errors)) {
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'Produk berhasil disimpan.');
                    return $this->redirect(['barangproduksi/index']);
                } else {
                    $transaction->rollBack();
                    \Yii::$app->session->setFlash('error', 'Gagal menyimpan data: ' . implode(', ', $errors));
                }
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        return $this->render('create', [
            'barangList' => $barangList,
        ]);
        
        
    }

    /**
     * Updates an existing Barangproduksi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $barang_produksi_id Barang ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($barang_produksi_id)
    // {
    //     $model = $this->findModel($barang_produksi_id);

    //     if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'barang_produksi_id' => $model->barang_produksi_id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }
    public function actionUpdate($barang_produksi_id)
    {
        $model = $this->findModel($barang_produksi_id);
        
        // Ambil list barang mentah untuk dropdown BOM (jenis_barang = 1)
        $barangList = \app\models\Barang::find()
            ->where(['jenis_barang' => 1])
            ->select(['CONCAT(kode_barang, " - ", nama_barang) as nama', 'barang_id'])
            ->indexBy('barang_id')
            ->asArray()
            ->all();
        
        // Ambil data BOM yang sudah ada
        $existingBom = \app\models\BomDetail::find()
            ->where(['barang_produksi_id' => $barang_produksi_id])
            ->with('barang')
            ->all();

        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $transaction = \Yii::$app->db->beginTransaction();
            
            try {
                $success = true;
                $errors = [];

                // Update data barang produksi
                if (isset($post['Barangproduksi'])) {
                    $model->kode_barang_produksi = $post['Barangproduksi']['kode_barang_produksi'] ?? '';
                    $model->nama = $post['Barangproduksi']['nama'] ?? '';
                    $model->nama_jenis = $post['Barangproduksi']['nama_jenis'] ?? '';
                    $model->ukuran = $post['Barangproduksi']['ukuran'] ?? '';
                    $model->deskripsi = $post['Barangproduksi']['deskripsi'] ?? '';
                    
                    if (!$model->save()) {
                        $errors[] = "Gagal update produk: " . json_encode($model->errors);
                        $success = false;
                    }
                }

                if ($success) {
                    // Hapus BOM lama untuk produk ini
                    \app\models\BomDetail::deleteAll(['barang_produksi_id' => $barang_produksi_id]);
                    
                    // Simpan BOM baru
                    if (isset($post['bom']) && is_array($post['bom'])) {
                        foreach ($post['bom'] as $bomIndex => $bomData) {
                            if (empty($bomData['barang_id']) || empty($bomData['qty'])) {
                                continue; // Skip jika kosong
                            }

                            $bomBarang = new \app\models\BomDetail();
                            $bomBarang->barang_produksi_id = $barang_produksi_id;
                            $bomBarang->barang_id = $bomData['barang_id'];
                            $bomBarang->qty_BOM = $bomData['qty'];
                            $bomBarang->catatan = $bomData['catatan'] ?? '';
                            
                            if (!$bomBarang->save()) {
                                $errors[] = "BOM Bahan #" . ($bomIndex + 1) . ": " . json_encode($bomBarang->errors);
                                $success = false;
                            }
                        }
                    }
                }

                if ($success && empty($errors)) {
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'Produk berhasil diupdate.');
                    return $this->redirect(['view', 'barang_produksi_id' => $model->barang_produksi_id]);
                } else {
                    $transaction->rollBack();
                    \Yii::$app->session->setFlash('error', 'Gagal update data: ' . implode(', ', $errors));
                }
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'barangList' => $barangList,
            'existingBom' => $existingBom,
        ]);
    }

    /**
     * Deletes an existing Barangproduksi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $barang_produksi_id Barang ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($barang_produksi_id)
    // {
    //     $model = $this->findModel($barang_produksi_id);

    //     // Periksa apakah tabel laporanproduksi ada
    //     $db = Yii::$app->db;
    //     $tableSchema = $db->getSchema()->getTableSchema('laporanproduksi');

    //     if ($tableSchema === null) {
    //         Yii::$app->session->setFlash('error', 'Tabel laporan produksi tidak ditemukan. Hubungi administrator.');
    //         return $this->redirect(['index']);
    //     }

    //     $isBarangUsedInLaporanProduksi = (new \yii\db\Query())
    //         ->from('laporanproduksi')
    //         ->where(['nama_barang' => $barang_produksi_id]) 
    //         ->exists();

    //     if ($isBarangUsedInLaporanProduksi) {
    //         Yii::$app->session->setFlash('error', 'Barang ini tidak dapat dihapus karena sedang digunakan di laporan produksi.');
    //         return $this->redirect(['index']);
    //     }

    //     $model->delete();
    //     Yii::$app->session->setFlash('success', 'Barang berhasil dihapus.');

    //     return $this->redirect(['index']);
    // }
    public function actionDelete($barang_produksi_id)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Periksa apakah tabel laporanproduksi ada
            $db = Yii::$app->db;
            $tableSchema = $db->getSchema()->getTableSchema('laporanproduksi');

            if ($tableSchema !== null) {
                // Cek apakah barang digunakan di laporan produksi
                $isBarangUsedInLaporanProduksi = (new \yii\db\Query())
                    ->from('laporanproduksi')
                    ->where(['nama_barang' => $barang_produksi_id]) 
                    ->exists();

                if ($isBarangUsedInLaporanProduksi) {
                    Yii::$app->session->setFlash('error', 'Barang ini tidak dapat dihapus karena sedang digunakan di laporan produksi.');
                    return $this->redirect(['index']);
                }
            }

            // Hapus semua BOM detail terlebih dahulu
            \app\models\BomDetail::deleteAll(['barang_produksi_id' => $barang_produksi_id]);
            
            // Hapus barang produksi
            $this->findModel($barang_produksi_id)->delete();
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Barang dan BOM berhasil dihapus.');

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Barangproduksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $barang_produksi_id Barang ID
     * @return Barangproduksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($barang_produksi_id)
    {
        if (($model = Barangproduksi::findOne(['barang_produksi_id' => $barang_produksi_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
