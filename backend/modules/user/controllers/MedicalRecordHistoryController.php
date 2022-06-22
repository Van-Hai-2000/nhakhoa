<?php

namespace backend\modules\user\controllers;

use backend\models\UserAdmin;
use common\models\product\Product;
use Yii;
use common\models\medical_record\MedicalRecordHistory;
use common\models\medical_record\MedicalRecordHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MedicalRecordHistoryController implements the CRUD actions for MedicalRecordHistory model.
 */
class MedicalRecordHistoryController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new MedicalRecordHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetForm($id){
        $products = Product::find()->where(['status' => 1])->asArray()->all();
        $products = array_column($products,'name','id');
        $doctor = UserAdmin::getDoctor();
        $user_admin = Yii::$app->user->getIdentity();
        $history_id = isset($_POST['history_id']) && $_POST['history_id'] ? $_POST['history_id'] : '';
        if($history_id){
            $model = MedicalRecordHistory::findOne($history_id);
            $model->created_at = date('Y-m-d\TH:i', $model->created_at);
        }else{
            $model = new MedicalRecordHistory();
            $model->created_at = date('Y-m-d\TH:i', time());
            $model->branch_id = isset($user_admin->branch_id) && $user_admin->branch_id ? $user_admin->branch_id : '';
        }
        return $this->renderPartial('item',['products' => $products, 'doctor' => $doctor, 'model' => $model,'id' => $id]);
    }

    public function actionAddHistory($id,$history_id){
        if($history_id){
            $model = MedicalRecordHistory::findOne($history_id);
        }else{
            $model = new MedicalRecordHistory();
        }
        $user_admin = Yii::$app->user->getIdentity();
        if($model->load($_POST)){
            $model->created_at = strtotime($model->created_at);
            $model->admin_name = $user_admin->fullname;
            if($model->save()){
                return json_encode([
                    'success' => true,
                ]);
            }else{
                return json_encode([
                    'success' => false,
                    'errors' => $model->getErrors()
                ]);
            }
        }
        return json_encode([
            'success' => false,
        ]);
    }

    public function actionDelete($id){
        $model = MedicalRecordHistory::findOne($id);
        if($model->delete()){
            return $this->redirect(['/user/medical-record/information','id' => $model->medical_record_id]);
        }
    }
}
