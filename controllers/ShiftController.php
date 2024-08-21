<?php
namespace app\controllers;

use Yii;
use app\models\Shift;
use app\models\ShiftSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;

/**
 * ShiftController implements the CRUD actions for Shift model.
 */
class ShiftController extends Controller
{
    /**s
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
     * Lists all Shift models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ShiftSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Shift model.
     * @param int $shift_id Shift ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($shift_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($shift_id),
        ]);
    }

    /**
     * Creates a new Shift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Shift();

        if ($this->request->isPost) {
            $model->load($this->request->post());

            // Set the user_id to the currently logged-in user
            $model->user_id = Yii::$app->user->id;

            // Custom time logic
            if ($model->waktu_kerja === 'custom') {
                $startTime = $this->request->post('Shift')['start_time'];
                $endTime = $this->request->post('Shift')['end_time'];

                if ($startTime && $endTime) {
                    $startTime = \DateTime::createFromFormat('H:i', $startTime);
                    $endTime = \DateTime::createFromFormat('H:i', $endTime);
                    
                    if ($startTime && $endTime) {
                        $interval = $startTime->diff($endTime);
                        $hours = $interval->h + ($interval->i / 60);
                        $model->waktu_kerja = $hours / 9; // Calculate the percentage based on a 9-hour shift
                    } else {
                        $model->addError('start_time', 'Invalid start time format.');
                        $model->addError('end_time', 'Invalid end time format.');
                    }
                } else {
                    $model->addError('start_time', 'Start time is required.');
                    $model->addError('end_time', 'End time is required.');
                }
            }

            try {
                if ($model->save()) {
                    return $this->redirect(['view', 'shift_id' => $model->shift_id]);
                }
            } catch (IntegrityException $e) {
                $model->addError('user_id', 'Duplicate entry for user ID.');
                // Optionally, log the error or perform additional actions
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Shift model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $shift_id Shift ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($shift_id)
    {
        $model = $this->findModel($shift_id);

        if ($this->request->isPost) {
            $model->load($this->request->post());

       
            $model->user_id = Yii::$app->user->id;

           
            if ($model->waktu_kerja === 'custom') {
                $startTime = $this->request->post('Shift')['start_time'];
                $endTime = $this->request->post('Shift')['end_time'];

                if ($startTime && $endTime) {
                    $startTime = \DateTime::createFromFormat('H:i', $startTime);
                    $endTime = \DateTime::createFromFormat('H:i', $endTime);
                    
                    if ($startTime && $endTime) {
                        $interval = $startTime->diff($endTime);
                        $hours = $interval->h + ($interval->i / 60);
                        $model->waktu_kerja = $hours / 9; 
                    } else {
                        $model->addError('start_time', 'Invalid start time format.');
                        $model->addError('end_time', 'Invalid end time format.');
                    }
                } else {
                    $model->addError('start_time', 'Start time is required.');
                    $model->addError('end_time', 'End time is required.');
                }
            }

            try {
                if ($model->save()) {
                    return $this->redirect(['view', 'shift_id' => $model->shift_id]);
                }
            } catch (IntegrityException $e) {
                $model->addError('user_id', 'Duplicate entry for user ID.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Shift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $shift_id Shift ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($shift_id)
    {
        $this->findModel($shift_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Shift model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $shift_id Shift ID
     * @return Shift the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($shift_id)
    {
        if (($model = Shift::findOne(['shift_id' => $shift_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
