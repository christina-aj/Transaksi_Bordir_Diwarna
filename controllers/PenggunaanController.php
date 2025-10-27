<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Barang;
use app\models\Gudang;
use app\models\Penggunaan;
use app\models\PenggunaanSearch;
use app\models\PenggunaanDetail;
use app\models\User;
use Yii;
use yii\base\Model;
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
     * Lists all Penggunaan models.
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
        $model = $this->findModel($penggunaan_id);
        $penggunaanDetails = $model->penggunaanDetails;
        
        return $this->render('view', [
            'model' => $model,
            'penggunaanDetails' => $penggunaanDetails,
        ]);
    }

    /**
     * Creates a new Penggunaan model.
     * @return string|\yii\web\Response
     */


    public function actionCreate()
    {
         // Ambil permintaan_id dari URL (jika ada)
        $permintaanId = Yii::$app->request->get('permintaan_id');
        
        $modelPenggunaan = new Penggunaan();

        // Set default data
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $modelPenggunaan->tanggal = $now->format('Y-m-d');
        $modelPenggunaan->created_at = $now->format('Y-m-d H:i:s');
        $modelPenggunaan->updated_at = $now->format('Y-m-d H:i:s');
        // $modelPenggunaan->tanggal = date('Y-m-d');
        $modelPenggunaan->user_id = Yii::$app->user->identity->user_id;
        $modelPenggunaan->total_item_penggunaan = 0;
        $modelPenggunaan->status_penggunaan = 0;
        // $modelPenggunaan->created_at = date('Y-m-d H:i:s');
        // $modelPenggunaan->updated_at = date('Y-m-d H:i:s');

        // SIMPAN PERMINTAAN_ID
        $modelPenggunaan->permintaan_id = $permintaanId;

        // Generate kode penggunaan
        $kodeSementara = Penggunaan::find()->max('penggunaan_id') + 1;
        $modelPenggunaan->kode_penggunaan = 'PG-' . str_pad($kodeSementara, 3, '0', STR_PAD_LEFT);

        // Debug: tampilkan data sebelum save
        // var_dump($modelPenggunaan->attributes);
        // var_dump($modelPenggunaan->validate());
        // var_dump($modelPenggunaan->getErrors());
        // die();

        // Simpan data dan langsung redirect seperti pemesanan
        if ($modelPenggunaan->save()) {
            // Yii::$app->session->setFlash('success', 'Penggunaan berhasil dibuat.');
            // Set session untuk ID Penggunaan seperti pada pemesanan
            Yii::$app->session->set('temporaryPenggunaanId', $modelPenggunaan->penggunaan_id);
            
            // // Debug - cek session
            // var_dump('Session set:', Yii::$app->session->get('temporaryPenggunaanId'));
            // die(); // temporary untuk testing
            
            //Set flash message jika dari permintaan
            if ($permintaanId) {
                Yii::$app->session->setFlash('info', 'Form ini diisi berdasar kebutuhan permintaan. Review dulu dan klik Save.');
            }

            // Yii::$app->session->setFlash('success', 'Penggunaan berhasil dibuat.');
            
            return $this->redirect([
                'add-details', 
                'penggunaan_id' => $modelPenggunaan->penggunaan_id,
                'permintaan_id' => $permintaanId // Kirim permintaan_id ke add-details
            ]);
            var_dump('Redirect URL:', $redirectUrl);
            die();
        } else {
            Yii::$app->session->setFlash('error', 'Gagal membuat penggunaan.');
            return $this->redirect(['index']);
        }
        // if ($modelPenggunaan->load(Yii::$app->request->post()) && $modelPenggunaan->validate()) {
        //     if ($modelPenggunaan->save()) {
        //         Yii::$app->session->setFlash('success', 'Penggunaan berhasil dibuat.');
        //         // return $this->redirect(['view', 'penggunaan_id' => $modelPenggunaan->penggunaan_id]);
        //         return $this->redirect(['add-details', 'penggunaan_id' => $modelPenggunaan->$penggunaanId]);
        // } else {
        //     Yii::$app->session->setFlash('error', 'Gagal menyimpan penggunaan.');
        //     return $this->redirect(['index']);

    }

    /**
     * Add details to penggunaan
     * @param int $penggunaan_id
     * @return string|\yii\web\Response
     */
    public function actionAddDetails($penggunaan_id)
    {
        
        // Ambil permintaan_id dari URL (jika ada)
        $permintaanId = Yii::$app->request->get('permintaan_id');
        // Ambil ID Penggunaan dari session untuk validasi
        $temporaryId = Yii::$app->session->get('temporaryPenggunaanId');

        // Debug
        // var_dump('Temporary ID from session:', $temporaryId);
        // var_dump('Current penggunaan_id:', $penggunaan_id);
        // var_dump('Match:', $temporaryId == $penggunaan_id);
        // var_dump('Session exists:', !empty($temporaryId));
        // die(); // temporary untuk testing

        if (!$temporaryId || $temporaryId != $penggunaan_id) {
            Yii::$app->session->setFlash('error', 'Session penggunaan tidak valid.');
            return $this->redirect(['index']);
        }

        $modelPenggunaan = Penggunaan::findOne($penggunaan_id);
        if (!$modelPenggunaan) {
            throw new NotFoundHttpException("Data penggunaan tidak ditemukan.");
        }

        $modelDetails = [new PenggunaanDetail()];

        if (Yii::$app->request->isPost) {
            $modelDetails = ModelHelper::createMultiple(PenggunaanDetail::classname());
            Model::loadMultiple($modelDetails, Yii::$app->request->post());

            if (Model::validateMultiple($modelDetails)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelDetails as $index => $modelDetail) {
                        $barang = Barang::findOne($modelDetail->barang_id);
                        $modelDetail->penggunaan_id = $penggunaan_id;
                        $modelDetail->created_at = date('Y-m-d H:i:s');
                        $modelDetail->gudang_id = null; // FK ke record gudang yang akan dikurangi

                        if (!$modelDetail->save()) {
                            throw new \Exception("Gagal menyimpan detail penggunaan ke-{$index}: " . json_encode($modelDetail->getErrors()));
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Detail penggunaan berhasil disimpan.');
                    return $this->redirect(['view', 'penggunaan_id' => $penggunaan_id]);

                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Validasi gagal.');
            }
        }

        return $this->render('create', [
            'modelPenggunaan' => $modelPenggunaan,
            'modelDetails' => $modelDetails,
            'permintaanId' => $permintaanId,
        ]);
    }

    /**
     * Updates an existing Penggunaan model.
     * @param int $penggunaan_id Penggunaan ID
     * @return string|\yii\web\Response
     */

    public function actionUpdate($penggunaan_id)
    {
        $modelPenggunaan = Penggunaan::findOne($penggunaan_id);
        if (!$modelPenggunaan) {
            throw new NotFoundHttpException("Data penggunaan tidak ditemukan.");
        }

        // Ambil detail penggunaan yang terkait
        $modelDetails = PenggunaanDetail::findAll(['penggunaan_id' => $modelPenggunaan->penggunaan_id]);

        // Set nama_barang untuk setiap modelDetail jika dalam mode update (SAMA SEPERTI PEMESANAN)
        foreach ($modelDetails as $modelDetail) {
            $modelDetail->nama_barang = $modelDetail->getNamaBarang();
            $modelDetail->kode_barang = $modelDetail->getKodeBarang();
        }

        // if (Yii::$app->request->isPost) {
        if (Yii::$app->request->method === 'POST') {
            $modelPenggunaan->load(Yii::$app->request->post());

            // Ambil data detail dari POST (SAMA SEPERTI PEMESANAN)
            $detailData = Yii::$app->request->post('PenggunaanDetail', []); // NAMA HARUS SAMA DENGAN JS
            $isValid = $modelPenggunaan->validate();

            // Loop untuk menyimpan atau memperbarui detail yang ada
            foreach ($modelDetails as $index => $modelDetail) {
                if (isset($detailData[$index])) {
                    $modelDetail->setAttributes($detailData[$index]);
                    $isValid = $modelDetail->validate() && $isValid;
                }
            }

            // Loop untuk menambah detail baru jika ada data baru
            $newDetails = [];
            foreach ($detailData as $index => $data) {
                if (!isset($modelDetails[$index])) { // Detail baru
                    $newDetail = new PenggunaanDetail();
                    $newDetail->penggunaan_id = $modelPenggunaan->penggunaan_id;
                    $newDetail->setAttributes($data);
                    $newDetails[] = $newDetail;
                    $isValid = $newDetail->validate() && $isValid;
                }
            }

            // Hapus PenggunaanDetail yang dihapus oleh user (SAMA SEPERTI PEMESANAN)
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $deletedDetails = json_decode(Yii::$app->request->post('deleteRows', '[]'), true);
                if (is_array($deletedDetails)) {
                    foreach ($deletedDetails as $deletedId) {
                        $deletedDetail = PenggunaanDetail::findOne($deletedId);
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

            // Jika valid, simpan perubahan di Penggunaan dan detailnya
            if ($isValid) {
                $modelPenggunaan->save(false); // Simpan tanpa validasi karena sudah divalidasi di atas

                // Simpan detail yang diubah
                foreach ($modelDetails as $modelDetail) {
                    $modelDetail->save(false); // Simpan tanpa validasi
                }

                // Simpan detail baru
                foreach ($newDetails as $newDetail) {
                    $newDetail->save(false);
                }

                Yii::$app->session->setFlash('success', 'Penggunaan berhasil diperbarui.');
                return $this->redirect(['view', 'penggunaan_id' => $modelPenggunaan->penggunaan_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui data penggunaan. Harap periksa kembali data yang dimasukkan.');
            }
        }

        return $this->render('update', [
            'modelPenggunaan' => $modelPenggunaan,
            'modelDetails' => $modelDetails,
        ]);
    }

    // public function actionUpdate($penggunaan_id)
    // {
    //     // Debug POST data
    //     // DEBUG: Tambahkan ini di baris paling atas
    //     echo "<h3>DEBUG INFO:</h3>";
    //     echo "<pre>Request Method: " . Yii::$app->request->method . "</pre>";
    //     echo "<pre>Is POST: " . (Yii::$app->request->isPost ? 'YES' : 'NO') . "</pre>";
    //     echo "<pre>Raw POST: ";
    //     print_r($_POST);
    //     echo "</pre>";
    //     echo "<pre>Yii POST: ";
    //     print_r(Yii::$app->request->post());
    //     echo "</pre>";
        
    //     // SEMENTARA STOP DI SINI UNTUK LIHAT DATA
    //     exit('=== DEBUG STOP ===');
    
    //     $modelPenggunaan = Penggunaan::findOne($penggunaan_id);
    //     if (!$modelPenggunaan) {
    //         throw new NotFoundHttpException("Data penggunaan tidak ditemukan.");
    //     }

    //     $modelDetails = PenggunaanDetail::findAll(['penggunaan_id' => $modelPenggunaan->penggunaan_id]);

    //     if (Yii::$app->request->isPost) {
    //         $modelPenggunaan->load(Yii::$app->request->post());
    //         $detailData = Yii::$app->request->post('PenggunaanDetail', []);
    //         $isValid = $modelPenggunaan->validate();

    //         // Update existing details
    //         foreach ($modelDetails as $index => $modelDetail) {
    //             if (isset($detailData[$index])) {
    //                 $modelDetail->setAttributes($detailData[$index]);
    //                 $isValid = $modelDetail->validate() && $isValid;
    //             }
    //         }

    //         // Add new details
    //         $newDetails = [];
    //         foreach ($detailData as $index => $data) {
    //             if (!isset($modelDetails[$index])) {
    //                 $newDetail = new PenggunaanDetail();
    //                 $newDetail->penggunaan_id = $modelPenggunaan->penggunaan_id;
    //                 $newDetail->setAttributes($data);
    //                 $newDetails[] = $newDetail;
    //                 $isValid = $newDetail->validate() && $isValid;
    //             }
    //         }

    //         if ($isValid) {
    //             $modelPenggunaan->save(false);
    //             foreach ($modelDetails as $modelDetail) {
    //                 $modelDetail->save(false);
    //             }
    //             foreach ($newDetails as $newDetail) {
    //                 $newDetail->save(false);
    //             }

    //             Yii::$app->session->setFlash('success', 'Penggunaan berhasil diperbarui.');
    //             return $this->redirect(['view', 'penggunaan_id' => $modelPenggunaan->penggunaan_id]);
    //         }
    //     }

    //     return $this->render('update', [
    //         'modelPenggunaan' => $modelPenggunaan,
    //         'modelDetails' => $modelDetails,
    //     ]);
    // }

    /**
     * Update Qty dengan Area Gudang Selection
     * @param int $penggunaan_id
     * @return string|\yii\web\Response
     */
    // public function actionUpdateQty($penggunaan_id)
    // {
    //     echo "<h1>DEBUG: Controller dipanggil</h1>";
    //     echo "<p>Method: " . Yii::$app->request->method . "</p>";
    //     echo "<p>isPost: " . (Yii::$app->request->isPost ? 'TRUE' : 'FALSE') . "</p>";
    //     echo "<p>POST data: " . (empty($_POST) ? 'KOSONG' : 'ADA DATA') . "</p>";
        
    //     if (!empty($_POST)) {
    //         echo "<h2>RAW POST DATA:</h2>";
    //         echo "<pre>";
    //         print_r($_POST);
    //         echo "</pre>";
    //     }
        
    //     // exit();

    //     $modelPenggunaan = Penggunaan::findOne($penggunaan_id);
    //     if (!$modelPenggunaan) {
    //         throw new NotFoundHttpException("Data penggunaan tidak ditemukan.");
    //     }

    //     if ($modelPenggunaan->status_penggunaan != 0) {
    //         Yii::$app->session->setFlash('warning', 'Penggunaan ini sudah tidak dapat diupdate.');
    //         return $this->redirect(['view', 'penggunaan_id' => $penggunaan_id]);
    //     }

    //     $modelDetails = PenggunaanDetail::findAll(['penggunaan_id' => $penggunaan_id]);
        
    //     // Ambil stock per area untuk setiap barang
    //     $stockPerArea = [];
    //     foreach ($modelDetails as $detail) {
    //         $stocks = Gudang::find()
    //             ->where(['barang_id' => $detail->barang_id])
    //             ->andWhere(['>', 'quantity_akhir', 0])
    //             ->orderBy(['area_gudang' => SORT_ASC, 'created_at' => SORT_DESC])
    //             ->all();
            
    //         $areaStock = [];
    //         $processedAreas = [];
            
    //         foreach ($stocks as $stock) {
    //             // Ambil hanya record terbaru per area
    //             if (!in_array($stock->area_gudang, $processedAreas)) {
    //                 $areaStock[$stock->area_gudang] = [
    //                     'id_gudang' => $stock->id_gudang,
    //                     'quantity_akhir' => $stock->quantity_akhir,
    //                     'tanggal' => $stock->tanggal
    //                 ];
    //                 $processedAreas[] = $stock->area_gudang;
    //             }
    //         }
            
    //         $stockPerArea[$detail->barang_id] = $areaStock;
    //     }

    //     if (Yii::$app->request->isPost) {
    //         $detailsData = Yii::$app->request->post('details', []);
            
    //         $transaction = Yii::$app->db->beginTransaction();
    //         try {
    //             // Hapus detail lama
    //             PenggunaanDetail::deleteAll(['penggunaan_id' => $penggunaan_id]);
                
    //             foreach ($detailsData as $detailData) {
    //                 $originalBarangId = $detailData['barang_id'];
    //                 $originalQuantity = $detailData['jumlah_digunakan'];
    //                 $catatan = $detailData['catatan'] ?? '';
    //                 $areas = $detailData['areas'] ?? [];
                    
    //                 // Validasi total quantity
    //                 $totalSelected = array_sum(array_column($areas, 'quantity'));
    //                 if ($totalSelected != $originalQuantity) {
    //                     throw new \Exception("Total quantity tidak sesuai untuk barang ID: {$originalBarangId}");
    //                 }
                    
    //                 // Buat detail baru per area
    //                 foreach ($areas as $areaData) {
    //                     $areaGudang = $areaData['area_gudang'];
    //                     $quantity = $areaData['quantity'];
                        
    //                     if ($quantity <= 0) continue;
                        
    //                     // Validasi stock tersedia
    //                     $currentStock = $this->getCurrentStockByArea($originalBarangId, $areaGudang);
    //                     if ($currentStock < $quantity) {
    //                         throw new \Exception("Stock tidak cukup di Area {$areaGudang}. Tersedia: {$currentStock}, diminta: {$quantity}");
    //                     }
                        
    //                     // Buat penggunaan detail baru
    //                     $newDetail = new PenggunaanDetail();
    //                     $newDetail->penggunaan_id = $penggunaan_id;
    //                     $newDetail->barang_id = $originalBarangId;
    //                     $newDetail->kode_barang = Barang::findOne($originalBarangId)->kode_barang;
    //                     $newDetail->nama_barang = Barang::findOne($originalBarangId)->nama_barang;
    //                     $newDetail->jumlah_digunakan = $quantity;
    //                     $newDetail->area_gudang = $areaGudang;
    //                     $newDetail->catatan = $catatan;
    //                     $newDetail->created_at = date('Y-m-d H:i:s');
                        
    //                     if (!$newDetail->save()) {
    //                         throw new \Exception("Gagal menyimpan detail: " . json_encode($newDetail->errors));
    //                     }

    //                     // UPDATE: Panggil updateGudangStock dengan detail ID
    //                     $this->updateGudangStock($originalBarangId, $areaGudang, $quantity, $penggunaan_id, $newDetail->gunadetail_id);
                        
    //                     // Update stock gudang
    //                     // $this->updateGudangStock($originalBarangId, $areaGudang, $quantity, $penggunaan_id);
    //                 }
    //             }
                
    //             // Update status penggunaan
    //             $modelPenggunaan->status_penggunaan = 1; // Complete
    //             $modelPenggunaan->updated_at = date('Y-m-d H:i:s');
                
    //             if (!$modelPenggunaan->save(false)) {
    //                 throw new \Exception('Gagal update status penggunaan');
    //             }
                
    //             $transaction->commit();
                
    //             Yii::$app->session->setFlash('success', 'Penggunaan berhasil diupdate dan stock berhasil dikurangi.');
    //             return $this->redirect(['view', 'penggunaan_id' => $penggunaan_id]);
                
    //         } catch (\Exception $e) {
    //             $transaction->rollBack();
    //             Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
    //         }
    //     }
    public function actionUpdateQty($penggunaan_id)
    {
        $modelPenggunaan = Penggunaan::findOne($penggunaan_id);
        if (!$modelPenggunaan) {
            throw new NotFoundHttpException("Data penggunaan tidak ditemukan.");
        }

        if ($modelPenggunaan->status_penggunaan != 0) {
            Yii::$app->session->setFlash('warning', 'Penggunaan ini sudah tidak dapat diupdate.');
            return $this->redirect(['view', 'penggunaan_id' => $penggunaan_id]);
        }

        $modelDetails = PenggunaanDetail::findAll(['penggunaan_id' => $penggunaan_id]);
        $stockPerArea = []; // ... existing stock code

        foreach ($modelDetails as $detail) {
            $stocks = Gudang::find()
                ->where(['barang_id' => $detail->barang_id])
                ->andWhere(['>', 'quantity_akhir', 0])
                ->andWhere(['kode' => 1])
                ->orderBy(['area_gudang' => SORT_ASC, 'created_at' => SORT_DESC])
                ->all();
            
            $areaStock = [];
            $processedAreas = [];
            
            foreach ($stocks as $stock) {
                // Ambil hanya record terbaru per area
                if (!in_array($stock->area_gudang, $processedAreas)) {
                    $areaStock[$stock->area_gudang] = [
                        'id_gudang' => $stock->id_gudang,
                        'quantity_akhir' => $stock->quantity_akhir,
                        'tanggal' => $stock->tanggal
                    ];
                    $processedAreas[] = $stock->area_gudang;
                }
            }
            
            $stockPerArea[$detail->barang_id] = $areaStock;
        }


        if (Yii::$app->request->isPost) {
            echo "<h3>DEBUG: Processing POST...</h3>";
            
            $detailsData = Yii::$app->request->post('details', []);
            echo "<pre>detailsData: "; print_r($detailsData); echo "</pre>";
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                echo "<p>Starting transaction...</p>";
                
                // Hapus detail lama
                $deleteResult = PenggunaanDetail::deleteAll(['penggunaan_id' => $penggunaan_id]);
                echo "<p>Deleted {$deleteResult} old records</p>";
                
                foreach ($detailsData as $index => $detailData) {
                    echo "<h4>Processing detail #{$index}</h4>";
                    
                    $originalBarangId = $detailData['barang_id'];
                    $originalQuantity = $detailData['jumlah_digunakan'];
                    $catatan = $detailData['catatan'] ?? '';
                    $areas = $detailData['areas'] ?? [];
                    
                    echo "<p>Barang ID: {$originalBarangId}, Original Qty: {$originalQuantity}</p>";
                    echo "<pre>Areas: "; print_r($areas); echo "</pre>";
                    
                    // Validasi total quantity
                    $totalSelected = array_sum(array_column($areas, 'quantity'));
                    echo "<p>Total selected: {$totalSelected}</p>";
                    
                    if ($totalSelected != $originalQuantity) {
                        throw new \Exception("Total quantity tidak sesuai untuk barang ID: {$originalBarangId}. Expected: {$originalQuantity}, Got: {$totalSelected}");
                    }
                    
                    // Buat detail baru per area
                    foreach ($areas as $areaIndex => $areaData) {
                        echo "<h5>Processing area #{$areaIndex}</h5>";
                        
                        $areaGudang = $areaData['area_gudang'];
                        $quantity = $areaData['quantity'];
                        
                        echo "<p>Area: {$areaGudang}, Quantity: {$quantity}</p>";
                        
                        if ($quantity <= 0) {
                            echo "<p>Skipping zero quantity</p>";
                            continue;
                        }
                        
                        // Buat penggunaan detail baru
                        $newDetail = new PenggunaanDetail();
                        $newDetail->penggunaan_id = $penggunaan_id;
                        $newDetail->barang_id = $originalBarangId;
                        $newDetail->kode_barang = Barang::findOne($originalBarangId)->kode_barang;
                        $newDetail->nama_barang = Barang::findOne($originalBarangId)->nama_barang;
                        $newDetail->jumlah_digunakan = $quantity;
                        $newDetail->area_gudang = $areaGudang;
                        $newDetail->catatan = $catatan;
                        $newDetail->created_at = date('Y-m-d H:i:s');
                        
                        echo "<pre>New detail attributes: "; print_r($newDetail->attributes); echo "</pre>";
                        
                        if (!$newDetail->save()) {
                            echo "<pre>Save failed. Errors: "; print_r($newDetail->errors); echo "</pre>";
                            throw new \Exception("Gagal menyimpan detail: " . json_encode($newDetail->errors));
                        }
                        
                        echo "<p>Detail saved with ID: {$newDetail->gunadetail_id}</p>";
                        
                        // PERBAIKAN: Panggil updateGudangStock setelah save berhasil
                        echo "<p>Updating stock gudang...</p>";
                        $this->updateGudangStock($originalBarangId, $areaGudang, $quantity, $penggunaan_id, $newDetail->gunadetail_id);
                        echo "<p>Stock updated successfully</p>";
                    }
                }
                
                // echo "<p>All details processed successfully</p>";
                
                // Update status penggunaan
                $modelPenggunaan->status_penggunaan = 1;
                $modelPenggunaan->updated_at = date('Y-m-d H:i:s');
                
                if (!$modelPenggunaan->save(false)) {
                    throw new \Exception('Gagal update status penggunaan');
                }
                
                echo "<p>Status updated to complete</p>";
                
                $transaction->commit();
                // echo "<p>Transaction committed!</p>";

                // UPDATE STATUS PERMINTAAN JADI "ON PROGRESS" (1)
                if (!empty($modelPenggunaan->permintaan_id)) {
                    $permintaan = \app\models\PermintaanPelanggan::findOne($modelPenggunaan->permintaan_id);
                    if ($permintaan && $permintaan->status_permintaan == 0) {
                        $permintaan->status_permintaan = 1; // On Progress
                        $permintaan->save(false);
                    }
                }

                Yii::$app->session->setFlash('success', 'Status : Complete. Barang dari gudang dipindahkan ke area produksi.');
                return $this->redirect(['view', 'penggunaan_id' => $penggunaan_id]);
                
                // exit("SUCCESS - Now redirecting..."); // Stop here to see if we reach this point
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
                echo "<pre>Stack trace: " . $e->getTraceAsString() . "</pre>";
                exit("FAILED");
            }
        }

        return $this->render('update-qty', [
            'modelPenggunaan' => $modelPenggunaan,
            'modelDetails' => $modelDetails,
            'stockPerArea' => $stockPerArea,
        ]);
    }

    /**
     * Update stock gudang untuk area tertentu
     */
    private function updateGudangStock($barangId, $areaGudang, $quantity, $penggunaanId, $penggunaanDetailId)
    {
        // Cari record gudang terbaru untuk area ini
        $gudangRecord = Gudang::find()
            ->where(['barang_id' => $barangId, 'area_gudang' => $areaGudang])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
        
        if (!$gudangRecord) {
            throw new \Exception("Stock tidak ditemukan untuk Area {$areaGudang}");
        }
        
        if ($gudangRecord->quantity_akhir < $quantity) {
            throw new \Exception("Stock tidak cukup di Area {$areaGudang}");
        }

        // Set id_gudang di penggunaan detail
        $penggunaanDetail = PenggunaanDetail::findOne($penggunaanDetailId);
        if ($penggunaanDetail) {
            $penggunaanDetail->id_gudang = $gudangRecord->id_gudang;
            $penggunaanDetail->save(false);
        }
        
        // Update record gudang
        // $gudangRecord->quantity_keluar += $quantity;
        // $gudangRecord->quantity_akhir -= $quantity;
        // $gudangRecord->update_at = date('Y-m-d H:i:s');
        // $gudangRecord->catatan = ($gudangRecord->catatan ? $gudangRecord->catatan . ' | ' : '') . "Penggunaan ID: {$penggunaanId}";

        // BUAT RECORD BARU DI GUDANG
        $newGudangRecord = new Gudang();
        $newGudangRecord->tanggal = date('Y-m-d');
        $newGudangRecord->barang_id = $barangId;
        $newGudangRecord->user_id = Yii::$app->user->id;
        $newGudangRecord->quantity_awal = $gudangRecord->quantity_akhir; // Stock sebelumnya
        $newGudangRecord->quantity_masuk = 0;
        $newGudangRecord->quantity_keluar = $quantity; // Yang dipakai
        $newGudangRecord->quantity_akhir = $gudangRecord->quantity_akhir - $quantity; // Stock baru
        $newGudangRecord->area_gudang = $areaGudang;
        $newGudangRecord->kode = 1; // Gudang
        $newGudangRecord->catatan = "Digunakan Produksi ID: {$penggunaanId}";
        $newGudangRecord->created_at = date('Y-m-d H:i:s');
        $newGudangRecord->update_at = date('Y-m-d H:i:s');
        
        if (!$newGudangRecord->save(false)) {
            throw new \Exception("Gagal buat record stock baru Area {$areaGudang}");
        }

        // 2. BUAT RECORD BARU DI STOCK PRODUKSI
        // Ambil stock produksi terbaru untuk barang ini
        $currentStockProduksi = Gudang::find()
            ->where(['barang_id' => $barangId, 'kode' => 2])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
        
        $stockAwalProduksi = $currentStockProduksi ? $currentStockProduksi->quantity_akhir : 0;

        $newStockRecord = new Gudang();
        $newStockRecord->tanggal = date('Y-m-d');
        $newStockRecord->barang_id = $barangId;
        $newStockRecord->user_id = Yii::$app->user->id;
        $newStockRecord->quantity_awal = $stockAwalProduksi; // Stock produksi sebelumnya
        $newStockRecord->quantity_masuk = $quantity; // Yang masuk ke produksi
        $newStockRecord->quantity_keluar = 0;
        $newStockRecord->quantity_akhir = $stockAwalProduksi + $quantity; // Stock produksi baru
        $newStockRecord->area_gudang = 5; // Area produksi
        $newStockRecord->kode = 2; // Stock produksi
        $newStockRecord->catatan = "Transfer dari Gudang - Penggunaan ID: {$penggunaanId}";
        $newStockRecord->created_at = date('Y-m-d H:i:s');
        $newStockRecord->update_at = date('Y-m-d H:i:s');
        
        if (!$newStockRecord->save(false)) {
            throw new \Exception("Gagal buat record stock produksi baru");
        }
    }



/**
     * Complete action - Form update detail dengan field area gudang (mirip update pemesanan)
     * @param int $penggunaan_id
     * @return string|\yii\web\Response
     */
    public function actionComplete($penggunaan_id)
    {
        $modelPenggunaan = Penggunaan::findOne($penggunaan_id);
        if (!$modelPenggunaan) {
            throw new NotFoundHttpException("Data penggunaan tidak ditemukan.");
        }

        if ($modelPenggunaan->status_penggunaan !== 'pending') {
            Yii::$app->session->setFlash('warning', 'Penggunaan ini sudah tidak dalam status pending.');
            return $this->redirect(['view', 'penggunaan_id' => $penggunaan_id]);
        }

        $modelDetails = PenggunaanDetail::findAll(['penggunaan_id' => $penggunaan_id]);

        // Cari gudang options per barang untuk dropdown
        $gudangOptions = [];
        foreach ($modelDetails as $detail) {
            $gudangRecords = Gudang::find()
                ->where(['barang_id' => $detail->barang_id])
                ->andWhere(['>', 'quantity_akhir', 0])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
            
            $options = [];
            foreach ($gudangRecords as $gudang) {
                $areaName = $this->getAreaName($gudang->area_gudang);
                $options[$gudang->id_gudang] = $areaName . ' - Stock: ' . $gudang->quantity_akhir . ' (' . $gudang->tanggal . ')';
            }
            
            $gudangOptions[$detail->gunadetail_id] = $options;
        }

        if (Yii::$app->request->isPost) {
            $detailsData = Yii::$app->request->post('PenggunaanDetail', []);
            $isValid = true;

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Update setiap detail dengan gudang_id yang dipilih
                foreach ($detailsData as $detail_id => $attributes) {
                    $detailModel = PenggunaanDetail::findOne($detail_id);
                    if ($detailModel) {
                        $detailModel->gudang_id = $attributes['gudang_id'];
                        $detailModel->jumlah_digunakan = $attributes['jumlah_digunakan'];
                        $detailModel->catatan = $attributes['catatan'] ?? '';
                        
                        if (!$detailModel->validate() || !$detailModel->save(false)) {
                            throw new \Exception("Gagal update detail ID: {$detail_id}");
                        }
                    }
                }

                // Proses stock out untuk semua detail
                foreach ($modelDetails as $detail) {
                    if (empty($detail->gudang_id)) {
                        throw new \Exception("Semua item harus memilih gudang.");
                    }

                    // Ambil record gudang yang dipilih
                    $selectedGudang = Gudang::findOne($detail->gudang_id);
                    if (!$selectedGudang) {
                        throw new \Exception("Record gudang tidak ditemukan.");
                    }

                    // Validasi stock
                    if ($selectedGudang->quantity_akhir < $detail->jumlah_digunakan) {
                        $areaName = $this->getAreaName($selectedGudang->area_gudang);
                        throw new \Exception("Stock tidak cukup di {$areaName}. Tersedia: {$selectedGudang->quantity_akhir}, diminta: {$detail->jumlah_digunakan}");
                    }

                    // UPDATE stock gudang
                    $selectedGudang->quantity_keluar += $detail->jumlah_digunakan;
                    $selectedGudang->quantity_akhir -= $detail->jumlah_digunakan;
                    $selectedGudang->update_at = date('Y-m-d H:i:s');
                    $selectedGudang->catatan = ($selectedGudang->catatan ? $selectedGudang->catatan . ' | ' : '') . 'Penggunaan ID: ' . $penggunaan_id;
                    
                    if (!$selectedGudang->save(false)) {
                        throw new \Exception('Gagal update stock gudang.');
                    }
                }

                // Update status penggunaan
                $modelPenggunaan->status_penggunaan = 'complete';
                $modelPenggunaan->updated_at = date('Y-m-d H:i:s');
                if (!$modelPenggunaan->save(false)) {
                    throw new \Exception('Gagal update status penggunaan.');
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Penggunaan selesai. Stock gudang berhasil dikurangi.');
                return $this->redirect(['view', 'penggunaan_id' => $penggunaan_id]);

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('complete', [
            'modelPenggunaan' => $modelPenggunaan,
            'modelDetails' => $modelDetails,
            'gudangOptions' => $gudangOptions,
        ]);
    }

    /**
     * Finish action - Proses stock out (mirip actionVerify di Pemesanan)
     * @param int $penggunaan_id
     * @return \yii\web\Response
     */
    public function actionFinish($penggunaan_id)
    {
        $modelPenggunaan = Penggunaan::findOne($penggunaan_id);
        if (!$modelPenggunaan) {
            Yii::$app->session->setFlash('error', 'Data penggunaan tidak ditemukan.');
            return $this->redirect(['index']);
        }

        $penggunaanDetails = PenggunaanDetail::findAll(['penggunaan_id' => $penggunaan_id]);

        // Cek apakah semua gudang_id sudah dipilih
        $allSelected = true;
        foreach ($penggunaanDetails as $detail) {
            if (empty($detail->gudang_id)) {
                $allSelected = false;
                break;
            }
        }

        if ($allSelected) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Loop untuk stock out setiap detail
                foreach ($penggunaanDetails as $detail) {
                    // Ambil record gudang yang dipilih
                    $selectedGudang = Gudang::findOne($detail->gudang_id);
                    if (!$selectedGudang) {
                        throw new \Exception("Record gudang tidak ditemukan.");
                    }

                    // Validasi stock mencukupi
                    if ($selectedGudang->quantity_akhir < $detail->jumlah_digunakan) {
                        $areaName = $this->getAreaName($selectedGudang->area_gudang);
                        throw new \Exception("Stock tidak mencukupi di {$areaName}. Tersedia: {$selectedGudang->quantity_akhir}, diminta: {$detail->jumlah_digunakan}");
                    }

                    // LANGSUNG UPDATE record gudang yang dipilih
                    $selectedGudang->quantity_keluar += $detail->jumlah_digunakan;
                    $selectedGudang->quantity_akhir -= $detail->jumlah_digunakan;
                    $selectedGudang->update_at = date('Y-m-d H:i:s');
                    $selectedGudang->catatan = ($selectedGudang->catatan ? $selectedGudang->catatan . ' | ' : '') . 'Penggunaan ID: ' . $penggunaan_id;
                    
                    if (!$selectedGudang->save(false)) {
                        throw new \Exception('Gagal update stock gudang untuk barang ID: ' . $detail->barang_id);
                    }
                }

                // Update status penggunaan menjadi complete
                $modelPenggunaan->status_penggunaan = 'complete';
                $modelPenggunaan->updated_at = date('Y-m-d H:i:s');
                if (!$modelPenggunaan->save(false)) {
                    throw new \Exception('Gagal mengupdate status penggunaan.');
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Penggunaan berhasil diselesaikan dan stock berhasil diperbarui.');

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal menyelesaikan penggunaan: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Penggunaan gagal diselesaikan. Pastikan semua item sudah memilih record gudang.');
        }

        return $this->redirect(['view', 'penggunaan_id' => $penggunaan_id]);
    }

    /**
     * Cancel penggunaan
     * @param int $penggunaan_id
     * @return \yii\web\Response
     */
    public function actionCancel($penggunaan_id)
    {
        $modelPenggunaan = Penggunaan::findOne($penggunaan_id);

        if ($modelPenggunaan) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                PenggunaanDetail::deleteAll(['penggunaan_id' => $penggunaan_id]);
                $modelPenggunaan->delete();
                // Clear session jika ini temporary record
                $temporaryId = Yii::$app->session->get('temporaryPenggunaanId');
                if ($temporaryId == $penggunaan_id) {
                    Yii::$app->session->remove('temporaryPenggunaanId');
                }
                $transaction->commit();
                // Yii::$app->session->setFlash('success', 'Penggunaan telah dibatalkan.');
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal membatalkan penggunaan: ' . $e->getMessage());
            }
        } else {
            // Tetap clear session meskipun record tidak ditemukan
            Yii::$app->session->remove('temporaryPenggunaanId');
            Yii::$app->session->setFlash('error', 'Data penggunaan tidak ditemukan.');
        }

        return $this->redirect(['index']);
    }

    public function actionCleanupSession()
    {
        Yii::$app->session->remove('temporaryPenggunaanId');
        return $this->redirect(['index']);
    }

    /**
     * Delete penggunaan
     * @param int $penggunaan_id
     * @return \yii\web\Response
     */
    public function actionDelete($penggunaan_id)
    {
        $this->findModel($penggunaan_id)->delete();
        return $this->redirect(['index']);
    }

    
    /**
     * Get BOM data untuk auto-fill form (AJAX)
     */
    public function actionGetBomData($permintaan_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        try {
            $permintaan = \app\models\PermintaanPelanggan::findOne($permintaan_id);
            if (!$permintaan || $permintaan->tipe_pelanggan != 2) {
                return ['success' => false, 'message' => 'Hanya untuk Polosan Ready'];
            }
            
            $kodePermintaan = $permintaan->generateKodePermintaan();
            $permintaanDetails = \app\models\PermintaanDetail::find()
                ->where(['permintaan_id' => $permintaan_id])
                ->andWhere(['IS NOT', 'barang_produksi_id', null])
                ->all();
            
            if (empty($permintaanDetails)) {
                return ['success' => false, 'message' => 'Tidak ada barang produksi'];
            }
            
            $bahanTotal = [];
            
            foreach ($permintaanDetails as $detail) {
                $bomBarang = \app\models\BomBarang::find()
                    ->where(['barang_produksi_id' => $detail->barang_produksi_id])
                    ->one();
                
                if (!$bomBarang) continue;
                
                $bomDetails = \app\models\BomDetail::find()
                    ->where(['BOM_barang_id' => $bomBarang->BOM_barang_id])
                    ->all();
                
                foreach ($bomDetails as $bomDetail) {
                    $barangId = $bomDetail->barang_id;
                    $totalQty = $bomDetail->qty_BOM * $detail->qty_permintaan;
                    
                    if (isset($bahanTotal[$barangId])) {
                        $bahanTotal[$barangId]['qty'] += $totalQty;
                    } else {
                        $barang = \app\models\Barang::findOne($barangId);
                        $bahanTotal[$barangId] = [
                            'barang_id' => $barangId,
                            'kode_barang' => $barang->kode_barang,
                            'nama_barang' => $barang->nama_barang,
                            'qty' => $totalQty,
                            'catatan' => "Digunakan Untuk Permintaan : {$kodePermintaan}"
                        ];
                    }
                }
            }
            
            if (empty($bahanTotal)) {
                return ['success' => false, 'message' => 'Tidak ada BOM'];
            }
            
            return [
                'success' => true,
                'data' => array_values($bahanTotal),
                'kode_permintaan' => $kodePermintaan
            ];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get current stock untuk barang_id dan area_gudang tertentu
     * @param int $barang_id
     * @param int $area_gudang
     * @return int
     */
    protected function getCurrentStockByArea($barangId, $areaGudang)
    {
        $currentStock = Gudang::find()
            ->where(['barang_id' => $barangId, 'area_gudang' => $areaGudang])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
            
        return $currentStock ? $currentStock->quantity_akhir : 0;
    }

    // /**
    //  * Get current stock berdasarkan barang_id - total dari semua area
    //  */
    // public function actionGetStock($barang_id, $show_detail = false)
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
    //     try {
    //         // Ambil stock dari semua area untuk barang ini
    //         $stockByArea = Gudang::find()
    //             ->select(['area_gudang', 'quantity_akhir', 'kode', 'tanggal', 'created_at'])
    //             ->where(['barang_id' => $barang_id])
    //             ->andWhere(['>', 'quantity_akhir', 0]) // Hanya area yang punya stock
    //             ->orderBy(['area_gudang' => SORT_ASC, 'created_at' => SORT_DESC])
    //             ->all();
            
    //         if (empty($stockByArea)) {
    //             return [
    //                 'success' => false,
    //                 'total_stock' => 0,
    //                 'areas' => [],
    //                 'message' => 'Stock tidak ditemukan di semua area'
    //             ];
    //         }
            
    //         // Hitung total stock dan group by area (ambil record terbaru per area)
    //         $totalStock = 0;
    //         $areaDetails = [];
    //         $processedAreas = [];
            
    //         foreach ($stockByArea as $stock) {
    //             // Ambil hanya record terbaru per area
    //             if (!in_array($stock->area_gudang, $processedAreas)) {
    //                 $totalStock += $stock->quantity_akhir;
    //                 $areaDetails[] = [
    //                     'area_gudang' => $stock->area_gudang,
    //                     'area_name' => $this->getAreaName($stock->area_gudang),
    //                     'quantity_akhir' => $stock->quantity_akhir,
    //                     'kode' => $stock->kode ?? '',
    //                     'tanggal' => $stock->tanggal ?? $stock->created_at
    //                 ];
    //                 $processedAreas[] = $stock->area_gudang;
    //             }
    //         }
            
    //         $result = [
    //             'success' => true,
    //             'total_stock' => $totalStock,
    //             'areas' => $areaDetails,
    //             'area_count' => count($areaDetails)
    //         ];
            
    //         return $result;
            
    //     } catch (\Exception $e) {
    //         return [
    //             'success' => false,
    //             'message' => 'Error: ' . $e->getMessage()
    //         ];
    //     }
    // }

/**
     * Get area name berdasarkan kode area
     * @param int $area_gudang
     * @return string
     */
    protected function getAreaName($area_gudang)
    {
        $areaNames = [
            1 => 'Area 1',
            2 => 'Area 2', 
            3 => 'Area 3',
            4 => 'Area 4',
        ];
        
        return isset($areaNames[$area_gudang]) ? $areaNames[$area_gudang] : 'Area Tidak Dikenal';
    }

    /**
     * Find model berdasarkan primary key
     * @param int $penggunaan_id
     * @return Penggunaan
     * @throws NotFoundHttpException
     */
    protected function findModel($penggunaan_id)
    {
        if (($model = Penggunaan::findOne(['penggunaan_id' => $penggunaan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}