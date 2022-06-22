<?php

namespace backend\modules\thuchi\controllers;

use Yii;
use common\models\medical_record\MedicalRecordLog;
use common\models\medical_record\MedicalRecordLogSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ThuchiLogController implements the CRUD actions for MedicalRecordLog model.
 */
class ThuchiLogController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MedicalRecordLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MedicalRecordLogSearch();
        $dataProvider = $searchModel->searchThuchi(Yii::$app->request->queryParams);

        $url = Url::to(['excel']);
        $url = $url . "?" . http_build_query($_GET);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $_GET,
            'url' => $url,
        ]);
    }

    /**
     * Displays a single MedicalRecordLog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MedicalRecordLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MedicalRecordLog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MedicalRecordLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MedicalRecordLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSetUrl()
    {
        $request = $_GET;
        $params = json_decode($request['params'], true);
        $params['MedicalRecordLogSearch']['time_start'] = strtotime($request['time_start']);
        $params['MedicalRecordLogSearch']['time_end'] = strtotime($request['time_end']);
        $url = Url::to(['index']);
        $final = $url . "?" . http_build_query($params);
        return $final;
    }

    /**
     * Finds the MedicalRecordLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MedicalRecordLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MedicalRecordLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
