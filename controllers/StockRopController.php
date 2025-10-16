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
                        'generate' => ['POST'], // Tambahkan ini
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
            
            foreach ($eoqRopData as $data) {
                // Cek apakah sudah ada di stock_rop
                $exists = StockRop::find()
                    ->where([
                        'barang_id' => $data['barang_id'],
                        'periode' => $data['periode']
                    ])
                    ->exists();
                
                if ($exists) continue;
                
                // Ambil data EOQ_ROP
                $eoqRop = Yii::$app->db->createCommand("
                    SELECT * FROM eoq_rop WHERE EOQ_ROP_id = :id
                ", [':id' => $data['latest_id']])->queryOne();
                
                if (!$eoqRop) continue;
                
                // Hitung stock barang dari gudang
                $stockBarang = Yii::$app->db->createCommand("
                    SELECT COALESCE(SUM(g.quantity_akhir), 0)
                    FROM gudang g
                    INNER JOIN (
                        SELECT barang_id, area_gudang, MAX(tanggal) AS tanggal_terakhir
                        FROM gudang
                        GROUP BY barang_id, area_gudang
                    ) latest
                    ON g.barang_id = latest.barang_id
                    AND g.area_gudang = latest.area_gudang
                    AND g.tanggal = latest.tanggal_terakhir
                    WHERE g.barang_id = :barang_id
                ", [':barang_id' => $eoqRop['barang_id']])->queryScalar();
                
                // Get safety stock dari barang
                $barang = Yii::$app->db->createCommand("
                    SELECT safety_stock FROM barang WHERE barang_id = :id
                ", [':id' => $eoqRop['barang_id']])->queryOne();
                
                $safetyStock = $barang ? $barang['safety_stock'] : 0;
                
                // Tentukan status pesan_barang
                $pesanBarang = 'Aman';
                if ($stockBarang <= $eoqRop['hasil_rop']) {
                    $pesanBarang = 'Pesan Sekarang';
                } elseif ($stockBarang <= $safetyStock) {
                    $pesanBarang = 'Perlu Diperhatikan';
                }
                
                // Insert ke stock_rop
                Yii::$app->db->createCommand()->insert('stock_rop', [
                    'barang_id' => $eoqRop['barang_id'],
                    'periode' => $eoqRop['periode'],
                    'stock_barang' => $stockBarang,
                    'safety_stock' => $safetyStock,
                    'jumlah_eoq' => $eoqRop['hasil_eoq'],
                    'jumlah_rop' => $eoqRop['hasil_rop'],
                    'pesan_barang' => $pesanBarang,
                ])->execute();
                
                $generated++;
            }
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', "Berhasil generate {$generated} data Stock ROP");
            
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