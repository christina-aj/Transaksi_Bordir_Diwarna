<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Barang;
use app\models\BarangSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\base\Model;

/**
 * BarangController implements the CRUD actions for Barang model.
 */
class BarangController extends Controller
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
     * Lists all Barang models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Barang model.
     * @param int $barang_id Barang ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($barang_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($barang_id),
        ]);
    }

    /**
     * Creates a new Barang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $modelBarangs = [new Barang()];

        if (Yii::$app->request->isPost) {
            // Load multiple instances
            $modelBarangs = ModelHelper::createMultiple(Barang::classname());
            if (Model::loadMultiple($modelBarangs, Yii::$app->request->post())) {
                foreach ($modelBarangs as $index => $modelBarang) {
                    Yii::info("Loaded ModelBarang #$index: " . json_encode($modelBarang->attributes), 'modelData');
                }
            } else {
                Yii::info("Data failed to load into modelBarangs.", 'loadError');
            }

            // Validate models
            if (Model::validateMultiple($modelBarangs)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelBarangs as $index => $modelBarang) {
                        $modelBarang->warna = $modelBarang->warna ?? null;
                        $modelBarang->created_at = date('Y-m-d H:i:s');
                        $modelBarang->updated_at = date('Y-m-d H:i:s');

                        if (!$modelBarang->save()) {
                            Yii::$app->session->setFlash('error', "Failed to save item #{$index}: " . json_encode($modelBarang->getErrors()));
                            throw new \yii\db\Exception('Failed to save items.');
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->set('modelBarangs', $modelBarangs);
                    return $this->redirect(['view', 'barang_id' => end($modelBarangs)->barang_id]);
                } catch (\yii\db\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Database error: ' . $e->getMessage());
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                }
            } else {
                $allErrors = [];
                foreach ($modelBarangs as $index => $modelBarang) {
                    $errors = $modelBarang->getErrors();
                    if (!empty($errors)) {
                        $allErrors[] = "Item #{$index} errors: " . json_encode($errors);
                    }
                }
                Yii::$app->session->setFlash('error', 'Validation failed: ' . implode(' | ', $allErrors));
            }
        }

        return $this->render('create', [
            'modelBarangs' => $modelBarangs,
            'isReadonly' => true,
        ]);
    }


    /**
     * Updates an existing Barang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $barang_id Barang ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($barang_id)
    {
        $model = $this->findModel($barang_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'barang_id' => $model->barang_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Barang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $barang_id Barang ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($barang_id)
    {
        $this->findModel($barang_id)->delete();

        return $this->redirect(['index']);
    }




    /**
     * Finds the Barang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $barang_id Barang ID
     * @return Barang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($barang_id)
    {
        if (($model = Barang::findOne(['barang_id' => $barang_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
