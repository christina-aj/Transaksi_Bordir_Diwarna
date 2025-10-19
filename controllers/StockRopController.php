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

    /**
     * Auto-generate Stock ROP dari data EOQ_ROP
     */
    public function actionGenerate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            // Ambil semua data EOQ_ROP terbaru per barang dan periode
            $eoqRopData = Yii::$app->db->createCommand("
                SELECT barang_id, periode, MAX(EOQ_ROP_id) as latest_id
                FROM eoq_rop
                GROUP BY barang_id, periode
            ")->queryAll();

            $generated = 0;
            $updated = 0;
            
            foreach ($eoqRopData as $data) {
                // Cari EOQ_ROP yang sesuai
                $eoqRop = EoqRop::findOne($data['latest_id']);
                
                if (!$eoqRop) continue;
                
                // Hitung stock barang dari gudang (data terbaru per area)
                $stockBarang = Yii::$app->db->createCommand("
                    SELECT COALESCE(SUM(g.quantity_akhir), 0)
                    FROM gudang g
                    INNER JOIN (
                        SELECT barang_id, area_gudang, MAX(tanggal) AS tanggal_terakhir
                        FROM gudang
                        WHERE barang_id = :barang_id
                        GROUP BY barang_id, area_gudang
                    ) latest
                    ON g.barang_id = latest.barang_id
                    AND g.area_gudang = latest.area_gudang
                    AND g.tanggal = latest.tanggal_terakhir
                    WHERE g.barang_id = :barang_id
                ", [':barang_id' => $eoqRop->barang_id])->queryScalar();
                
                // Ambil safety stock dari snapshot EOQ_ROP
                $safetyStock = $eoqRop->safety_stock_snapshot;
                
                // Tentukan status pesan_barang berdasarkan stock vs ROP
                $pesanBarang = 'Aman';
                if ($stockBarang <= $eoqRop->hasil_rop) {
                    $pesanBarang = 'Pesan Sekarang';
                } elseif ($stockBarang <= $safetyStock) {
                    $pesanBarang = 'Perlu Diperhatikan';
                }
                
                // Cek apakah data sudah ada di stock_rop
                $stockRop = StockRop::find()
                    ->where([
                        'barang_id' => $eoqRop->barang_id,
                        'periode' => $eoqRop->periode
                    ])
                    ->one();
                
                if ($stockRop) {
                    // Update data yang sudah ada
                    $stockRop->stock_barang = round($stockBarang, 2);
                    $stockRop->safety_stock = $safetyStock;
                    $stockRop->jumlah_eoq = round($eoqRop->hasil_eoq);
                    $stockRop->jumlah_rop = round($eoqRop->hasil_rop);
                    $stockRop->pesan_barang = $pesanBarang;
                    $stockRop->save(false);
                    $updated++;
                } else {
                    // Insert data baru
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
}