<?php

namespace backend\modules\user\controllers;

use common\models\branch\Branch;
use common\models\User;
use common\models\user\MedicalRecord;
use Yii;
use common\models\WaitingList;
use common\models\WaitingListSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WaitingListController implements the CRUD actions for WaitingList model.
 */
class WaitingListController extends Controller
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
     * Lists all WaitingList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WaitingListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
     * Displays a single WaitingList model.
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
     * Creates a new WaitingList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WaitingList();
        $user = Yii::$app->user->getIdentity();
        $branch = Branch::findOne($user->branch_id);
        if($branch){
            $model->branch_id = $user->branch_id;
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if(!$model->medical_record_id){
                $user = User::findOne($model->user_id);
                $medical = new MedicalRecord();
                $medical->user_id = $model->user_id;
                $medical->username = $user->username;
                $medical->phone = $user->phone;
                $medical->status = MedicalRecord::STATUS_WAITING;
                $medical->total_money = 0;
                $medical->money = 0;
                $medical->introduce = $user->introduce;
                $medical->introduce_id = $user->introduce_id;
                $medical->branch_id = $branch->id;
                if($medical->save()){
                    $model->medical_record_id = $medical->id;
                }else{
                    print_r('<pre>');
                    print_r($medical->getErrors());
                    die;
                }
            }
            //check stt theo ngày
            $beginOfDay = strtotime("today", time());
            $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;
            $check = WaitingList::find()->where(['branch_id' => $model->branch_id])
                ->andFilterWhere(['>', 'waiting_list.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'waiting_list.created_at', $endOfDay])
                ->orderBy('created_at DESC')->one();
            if($check){
                $model->stt = $check->stt + 1;
            }

            if($model->save()){
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WaitingList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WaitingList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WaitingList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WaitingList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WaitingList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSetUrl()
    {
        $request = $_GET;
        $params = json_decode($request['params'], true);
        $params['WaitingListSearch']['time_start'] = strtotime($request['time_start']);
        $params['WaitingListSearch']['time_end'] = strtotime($request['time_end']);
        $url = Url::to(['index']);
        $final = $url . "?" . http_build_query($params);
        return $final;
    }

    public function actionMedicalRecord($user_id)
    {
        $html = '<option value="">Chọn hồ sơ bệnh án</option>';
        $medical_record = \common\models\user\MedicalRecord::getMedicalRecord([
            'user_id' => $user_id
        ]);
        if($medical_record){
            foreach ($medical_record as $key => $item){
                $html .=  '<option value="'.$key.'">'.$item.'</option>';
            }
        }
        return $html;
    }
}
