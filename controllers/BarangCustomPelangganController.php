<?php

namespace app\controllers;

use app\models\BarangCustomPelanggan;
use app\models\BarangCustomPelangganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BarangCustomPelangganController implements the CRUD actions for BarangCustomPelanggan model.
 */
class BarangCustomPelangganController extends Controller
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
     * Lists all BarangCustomPelanggan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BarangCustomPelangganSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BarangCustomPelanggan model.
     * @param int $barang_custom_pelanggan_id Barang Custom Pelanggan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($barang_custom_pelanggan_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($barang_custom_pelanggan_id),
        ]);
    }

    /**
     * Creates a new BarangCustomPelanggan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new BarangCustomPelanggan();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             return $this->redirect(['view', 'barang_custom_pelanggan_id' => $model->barang_custom_pelanggan_id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Creates multiple custom products with BOM for a customer
     * @param int $pelanggan_id
     * @return string|\yii\web\Response
     */
    public function actionCreate($pelanggan_id)
    {
        // Validasi pelanggan
        $pelanggan = \app\models\MasterPelanggan::findOne($pelanggan_id);
        if (!$pelanggan) {
            throw new NotFoundHttpException('Pelanggan tidak ditemukan.');
        }

        // Ambil list barang mentah untuk dropdown BOM (jenis_barang = 1)
        $barangList = \app\models\Barang::find()
            ->where(['jenis_barang' => 1])
            ->select(['CONCAT(kode_barang, " - ", nama_barang) as nama', 'barang_id', 'unit_id'])
            ->indexBy('barang_id')
            ->asArray()
            ->all();

        // Ambil data unit untuk ditampilkan di form
        $unitList = \yii\helpers\ArrayHelper::map(
            \app\models\Unit::find()->all(),
            'unit_id',
            'nama_unit'
        );

        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $transaction = \Yii::$app->db->beginTransaction();
            
            try {
                $success = true;
                $errors = [];

                // Loop setiap produk custom yang diinput
                if (isset($post['products']) && is_array($post['products'])) {
                    foreach ($post['products'] as $index => $product) {
                        // Simpan Barang Custom Pelanggan
                        $barangCustom = new \app\models\BarangCustomPelanggan();
                        $barangCustom->pelanggan_id = $pelanggan_id;
                        $barangCustom->kode_barang_custom = $product['kode_barang'] ?? '';
                        $barangCustom->nama_barang_custom = $product['nama_barang'] ?? '';
                        $barangCustom->created_at = time();
                        $barangCustom->updated_at = time();
                        
                        if (!$barangCustom->save()) {
                            $errors[] = "Produk #" . ($index + 1) . ": " . json_encode($barangCustom->errors);
                            $success = false;
                            continue;
                        }

                        // Simpan BOM Custom untuk produk ini
                        if (isset($product['bom']) && is_array($product['bom'])) {
                            foreach ($product['bom'] as $bomIndex => $bomData) {
                                if (empty($bomData['barang_id']) || empty($bomData['qty'])) {
                                    continue; // Skip jika kosong
                                }

                                $bomCustom = new \app\models\BomCustom();
                                $bomCustom->barang_custom_pelanggan_id = $barangCustom->barang_custom_pelanggan_id;
                                $bomCustom->barang_id = $bomData['barang_id'];
                                $bomCustom->qty_per_unit = $bomData['qty'];
                                $bomCustom->created_at = date('Y-m-d H:i:s');
                                $bomCustom->updated_at = date('Y-m-d H:i:s');
                                
                                if (!$bomCustom->save()) {
                                    $errors[] = "BOM Produk #" . ($index + 1) . " Bahan #" . ($bomIndex + 1) . ": " . json_encode($bomCustom->errors);
                                    $success = false;
                                }
                            }
                        }
                    }
                }

                if ($success && empty($errors)) {
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'Produk custom berhasil disimpan.');
                    return $this->redirect(['master-pelanggan/view', 'pelanggan_id' => $pelanggan_id]);
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
            'pelanggan' => $pelanggan,
            'barangList' => $barangList,
            'unitList' => $unitList,
        ]);
    }

    /**
     * Update all custom products with BOM for a customer
     * @param int $pelanggan_id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($pelanggan_id)
    {
        // Validasi pelanggan
        $pelanggan = \app\models\MasterPelanggan::findOne($pelanggan_id);
        if (!$pelanggan) {
            throw new NotFoundHttpException('Pelanggan tidak ditemukan.');
        }

        // Ambil semua produk custom yang sudah ada
        $existingProducts = \app\models\BarangCustomPelanggan::find()
            ->where(['pelanggan_id' => $pelanggan_id])
            ->with('bomCustoms.barang')
            ->all();

        // Ambil list barang mentah untuk dropdown BOM (jenis_barang = 1)
        $barangList = \app\models\Barang::find()
            ->where(['jenis_barang' => 1])
            ->select(['CONCAT(kode_barang, " - ", nama_barang) as nama', 'barang_id', 'unit_id'])
            ->indexBy('barang_id')
            ->asArray()
            ->all();

        // Ambil data unit untuk ditampilkan di form
        $unitList = \yii\helpers\ArrayHelper::map(
            \app\models\Unit::find()->all(),
            'unit_id',
            'nama_unit'
        );

        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $transaction = \Yii::$app->db->beginTransaction();
            
            try {
                $success = true;
                $errors = [];

                // Hapus semua produk custom lama beserta BOM-nya
                foreach ($existingProducts as $oldProduct) {
                    // Hapus BOM dulu
                    \app\models\BomCustom::deleteAll(['barang_custom_pelanggan_id' => $oldProduct->barang_custom_pelanggan_id]);
                    // Hapus produk
                    $oldProduct->delete();
                }

                // Simpan produk baru (sama seperti create)
                if (isset($post['products']) && is_array($post['products'])) {
                    foreach ($post['products'] as $index => $product) {
                        // Skip jika kode dan nama kosong
                        if (empty($product['kode_barang']) && empty($product['nama_barang'])) {
                            continue;
                        }

                        // Simpan Barang Custom Pelanggan
                        $barangCustom = new \app\models\BarangCustomPelanggan();
                        $barangCustom->pelanggan_id = $pelanggan_id;
                        $barangCustom->kode_barang_custom = $product['kode_barang'] ?? '';
                        $barangCustom->nama_barang_custom = $product['nama_barang'] ?? '';
                        $barangCustom->created_at = time();
                        $barangCustom->updated_at = time();
                        
                        if (!$barangCustom->save()) {
                            $errors[] = "Produk #" . ($index + 1) . ": " . json_encode($barangCustom->errors);
                            $success = false;
                            continue;
                        }

                        // Simpan BOM Custom untuk produk ini
                        if (isset($product['bom']) && is_array($product['bom'])) {
                            foreach ($product['bom'] as $bomIndex => $bomData) {
                                if (empty($bomData['barang_id']) || empty($bomData['qty'])) {
                                    continue; // Skip jika kosong
                                }

                                $bomCustom = new \app\models\BomCustom();
                                $bomCustom->barang_custom_pelanggan_id = $barangCustom->barang_custom_pelanggan_id;
                                $bomCustom->barang_id = $bomData['barang_id'];
                                $bomCustom->qty_per_unit = $bomData['qty'];
                                $bomCustom->created_at = date('Y-m-d H:i:s');
                                $bomCustom->updated_at = date('Y-m-d H:i:s');
                                
                                if (!$bomCustom->save()) {
                                    $errors[] = "BOM Produk #" . ($index + 1) . " Bahan #" . ($bomIndex + 1) . ": " . json_encode($bomCustom->errors);
                                    $success = false;
                                }
                            }
                        }
                    }
                }

                if ($success && empty($errors)) {
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'Produk custom berhasil diupdate.');
                    return $this->redirect(['master-pelanggan/view', 'pelanggan_id' => $pelanggan_id]);
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
            'model' => $pelanggan,
            'existingProducts' => $existingProducts,
            'barangList' => $barangList,
            'unitList' => $unitList,
        ]);
    }

    /**
     * Deletes an existing BarangCustomPelanggan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $barang_custom_pelanggan_id Barang Custom Pelanggan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($barang_custom_pelanggan_id)
    {
        $this->findModel($barang_custom_pelanggan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BarangCustomPelanggan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $barang_custom_pelanggan_id Barang Custom Pelanggan ID
     * @return BarangCustomPelanggan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($barang_custom_pelanggan_id)
    {
        if (($model = BarangCustomPelanggan::findOne(['barang_custom_pelanggan_id' => $barang_custom_pelanggan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
