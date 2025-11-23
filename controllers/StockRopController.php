<?php

namespace app\controllers;

use app\models\StockRop;
use app\models\EoqRop;
use app\models\Barang;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

class StockRopController extends Controller
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
                        'generate' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionGenerate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $eoqRopData = Yii::$app->db->createCommand("
                SELECT barang_id, periode, MAX(EOQ_ROP_id) as latest_id
                FROM eoq_rop
                GROUP BY barang_id, periode
            ")->queryAll();

            $generated = 0;
            $updated = 0;
            
            foreach ($eoqRopData as $data) {
                $eoqRop = EoqRop::findOne($data['latest_id']);
                
                if (!$eoqRop) continue;
                
                $stockBarang = Yii::$app->db->createCommand(" 
                    SELECT COALESCE(SUM(g.quantity_akhir), 0) AS total_stok
                    FROM gudang g
                    WHERE g.barang_id = :barang_id
                    AND g.kode = 1
                    AND g.id_gudang IN (
                        SELECT MAX(g2.id_gudang)
                        FROM gudang g2
                        WHERE g2.barang_id = :barang_id
                        AND g2.kode = 1
                        AND g2.area_gudang = g.area_gudang
                        AND g2.created_at = (
                            SELECT MAX(g3.created_at)
                            FROM gudang g3
                            WHERE g3.barang_id = :barang_id
                            AND g3.kode = 1
                            AND g3.area_gudang = g.area_gudang
                        )
                        GROUP BY g2.area_gudang
                    )
                ", [':barang_id' => $eoqRop->barang_id])->queryScalar();
                
                $safetyStock = $eoqRop->safety_stock_snapshot;
                
                $pesanBarang = 'Aman';
                if ($stockBarang <= $eoqRop->hasil_rop) {
                    $pesanBarang = 'Pesan Sekarang';
                } elseif ($stockBarang <= $safetyStock) {
                    $pesanBarang = 'Perlu Diperhatikan';
                }
                
                $stockRop = StockRop::find()
                    ->where([
                        'barang_id' => $eoqRop->barang_id,
                        'periode' => $eoqRop->periode
                    ])
                    ->one();
                
                if ($stockRop) {
                    $stockRop->stock_barang = round($stockBarang, 2);
                    $stockRop->safety_stock = $safetyStock;
                    $stockRop->jumlah_eoq = round($eoqRop->hasil_eoq);
                    $stockRop->jumlah_rop = round($eoqRop->hasil_rop);
                    $stockRop->pesan_barang = $pesanBarang;
                    $stockRop->save(false);
                    $updated++;
                } else {
                    $stockRop = new StockRop();
                    $stockRop->barang_id = $eoqRop->barang_id;
                    $stockRop->periode = $eoqRop->periode;
                    $stockRop->stock_barang = round($stockBarang, 2);
                    $stockRop->safety_stock = $safetyStock;
                    $stockRop->jumlah_eoq = round($eoqRop->hasil_eoq);
                    $stockRop->jumlah_rop = round($eoqRop->hasil_rop);
                    $stockRop->pesan_barang = $pesanBarang;
                    $stockRop->save(false);
                    $generated++;
                }
            }
            
            $transaction->commit();
            
            $message = [];
            if ($generated > 0) $message[] = "generate {$generated} data baru";
            if ($updated > 0) $message[] = "update {$updated} data existing";
            
            Yii::$app->session->setFlash('success', "Berhasil " . implode(' dan ', $message) . " Stock ROP");
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Gagal generate data: ' . $e->getMessage());
        }
        
        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        $query = StockRop::find()
            ->with(['barang'])
            ->orderBy(['periode' => SORT_DESC, 'stock_rop_id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($stock_rop_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($stock_rop_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new StockRop();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'stock_rop_id' => $model->stock_rop_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($stock_rop_id)
    {
        $model = $this->findModel($stock_rop_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'stock_rop_id' => $model->stock_rop_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($stock_rop_id)
    {
        $this->findModel($stock_rop_id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($stock_rop_id)
    {
        if (($model = StockRop::findOne(['stock_rop_id' => $stock_rop_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Update stock_barang untuk barang tertentu secara real-time
     * Dipanggil otomatis dari Model Gudang afterSave event
     * 
     * @param int $barangId
     * @return bool
     */
    public static function updateStockForBarang($barangId)
    {
        try {
            // Hitung stock barang terbaru
            $stockBarang = Yii::$app->db->createCommand("
                SELECT COALESCE(SUM(g.quantity_akhir), 0) AS total_stok
                FROM gudang g
                WHERE g.barang_id = :barang_id
                AND g.kode = 1
                AND g.id_gudang IN (
                    SELECT MAX(g2.id_gudang)
                    FROM gudang g2
                    WHERE g2.barang_id = :barang_id
                    AND g2.kode = 1
                    AND g2.area_gudang = g.area_gudang
                    AND g2.created_at = (
                        SELECT MAX(g3.created_at)
                        FROM gudang g3
                        WHERE g3.barang_id = :barang_id
                        AND g3.kode = 1
                        AND g3.area_gudang = g.area_gudang
                    )
                    GROUP BY g2.area_gudang
                )
            ", [':barang_id' => $barangId])->queryScalar();
            
            // Update semua periode StockRop
            $stockRops = \app\models\StockRop::find()
                ->where(['barang_id' => $barangId])
                ->all();
            
            if (empty($stockRops)) {
                Yii::info("No StockRop data found for barang_id: {$barangId}", __METHOD__);
                return true;
            }
            
            foreach ($stockRops as $stockRop) {
                // Simpan status lama
                $oldStatus = $stockRop->pesan_barang;
                
                // Update stock
                $stockRop->stock_barang = round($stockBarang, 2);
                

                // Update status
                if ($stockBarang <= $stockRop->jumlah_rop) {
                    $stockRop->pesan_barang = 'Pesan Sekarang';
                } elseif ($stockBarang <= $stockRop->safety_stock) {
                    $stockRop->pesan_barang = 'Perlu Diperhatikan';
                } else {
                    $stockRop->pesan_barang = 'Aman';
                }

                $stockRop->save(false);

                // Kirim email jika status "Pesan Sekarang"
                if ($stockRop->pesan_barang == 'Pesan Sekarang') {
                    $barang = $stockRop->barang;
                    
                    \app\components\EmailHelper::sendRopNotification([
                        'stockRop' => $stockRop,
                        'barang' => $barang,
                        'currentStock' => $stockBarang,
                    ]);
                }
                
                // ===== OLD TPI BISA 1X TEST AJA, KIRIM EMAIL JIKA STATUS BERUBAH JADI "PESAN SEKARANG" =====
                // if ($stockRop->pesan_barang == 'Pesan Sekarang' && $oldStatus != 'Pesan Sekarang') {
                    
                //     // Cache key untuk mencegah spam email
                //     $cacheKey = "rop_email_sent_{$barangId}_{$stockRop->periode}";
                    
                //     // Cek apakah email sudah pernah dikirim dalam 1 jam terakhir
                //     if (!Yii::$app->cache->get($cacheKey)) {
                //         // Load relasi barang
                //         $barang = $stockRop->barang;
                        
                //         // Kirim email notifikasi
                //         $emailSent = \app\components\EmailHelper::sendRopNotification([
                //             'stockRop' => $stockRop,
                //             'barang' => $barang,
                //             'currentStock' => $stockBarang,
                //         ]);
                        
                //         if ($emailSent) {
                //             // Set cache 1 jam agar tidak kirim email berulang untuk rill
                //             // Yii::$app->cache->set($cacheKey, true, 3600);
                //             Yii::info("ROP notification email sent for barang_id: {$barangId}", __METHOD__);
                //         }
                //     } else {
                //         Yii::info("ROP email skipped (already sent) for barang_id: {$barangId}", __METHOD__);
                //     }
                // }
            }
            
            Yii::info("Stock ROP updated for barang_id: {$barangId}, new stock: {$stockBarang}", __METHOD__);
            return true;
            
        } catch (\Exception $e) {
            Yii::error("Failed to update Stock ROP: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}