<?php

namespace backend\modules\user\controllers;

use backend\models\UserAdmin;
use Yii;
use common\models\medical_record\Factory;
use common\models\medical_record\FactorySearch;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FactoryController implements the CRUD actions for Factory model.
 */
class FactoryController extends Controller
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
     * Lists all Factory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $admin = UserAdmin::findOne(Yii::$app->user->id);
        if ($admin->vai_tro == 1) {
            $searchModel = new FactorySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            $searchModel = new FactorySearch();
            $dataProvider = $searchModel->searchFactory(Yii::$app->request->queryParams);

            return $this->render('factory', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Factory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Factory::find()->where(['factory.id' => $id])->joinWith(['branch','userAdmin','user','loaimau'])->one();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Factory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Factory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Factory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $model = Factory::find()->where(['factory.id' => $id])->joinWith('user')->one();
        $model->time_return = isset($model->time_return) && $model->time_return ? date('Y-m-d\TH:i',$model->time_return) : '';

        if ($model->load(Yii::$app->request->post())) {
            $model->time_return = strtotime($model->time_return);
            if($model->save()){
                return $this->redirect(['index']);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Factory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Factory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Factory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Factory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
