<?php

namespace backend\modules\service\controllers;

use backend\models\UserAdmin;
use common\models\user\User;
use Yii;
use common\models\appointment\Appointment;
use common\models\appointment\AppointmentSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class AppointmentController extends Controller
{

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
        $session = Yii::$app->session;
        if (!$session->has('view')) {
            $year = date('Y',time());
            $firstDay = strtotime(date('Y-m-01'));
            $lastDay = strtotime(date('Y-m-t'));
            $total_day = date('d', $lastDay);
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($firstDay)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($lastDay)->format('Y-m-d 23:59:59'))->getTimestamp();
            $model = Appointment::find()->where(['status_delete' => 0])->andFilterWhere(['>', 'time', $beginOfDay])->andFilterWhere(['<', 'time', $endOfDay])->joinWith(['branch', 'userAdmin', 'productCategory'])->orderBy('time DESC')->all();
            if (\backend\modules\auth\components\Helper::checkRoute('/service/appointment/view-one') && !\backend\modules\auth\components\Helper::checkRoute('/service/appointment/index')) {
                $model = Appointment::find()->where(['doctor_id' => Yii::$app->user->id,'status_delete' => 0])->andFilterWhere(['>', 'time', $beginOfDay])->andFilterWhere(['<', 'time', $endOfDay])->joinWith(['branch','userAdmin'])->orderBy('time DESC')->all();
            }
            $list = [];
            if ($model) {
                foreach ($model as $value) {
                    $list[] = [
                        'title' => date('H:i', $value->time) . ' - ' . $value->name,
                        'start' => date('Y-m-d H', $value->time),
                        'end' => date('Y-m-d H', $value->time + 3600),
                        'className' => $value->status == 0 ? 'chuaden' : 'daden',
                        'cs_time' => $value->time ? date('H:i', $value->time) : '',
                        'cs_branch' => $value->branch->name ? $value->branch->name : '',
                        'cs_name' => $value->name ? $value->name : '',
                        'cs_medical' => $value->medical_record_id ? $value->medical_record_id : '',
                        'cs_phone' => '0'.(int)$value->phone.'',
                        'cs_doctor' => isset($value->userAdmin->fullname) && $value->userAdmin->fullname ? $value->userAdmin->fullname : '',
                        'cs_note' => preg_replace( "/\r|\n/", "", $value->description),
                        'cs_status' => $value->status == 0 ? 'Chưa đến' : 'Đã đến',
                        'cs_category' => isset($value->productCategory->name) && $value->productCategory->name ? $value->productCategory->name : '',
                    ];
                }
            }
            $list_year = [];
            for ($i = $year-10; $i <= $year+20 ; $i++){
                array_push($list_year,$i);
            }

            return $this->render('calendar', [
                'model' => $list,
                'list_year' => $list_year,
                'current_year' => $year,
                'total_day' => $total_day,
            ]);
        } else {
            $searchModel = new AppointmentSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'params' => $_GET,
            ]);
        }


    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate($id)
    {
        $model = new Appointment();
        if($id){
            $model_new = $this->findModel($id);
            $model->attributes = $model_new->attributes;
        }
        $request = Yii::$app->request->post();

        if(isset($request['description']) && $request['description']) {
            $request['description'] = ltrim($request['description']);
        }
        if ($model->load($request)) {
            $user = User::find()->where(['phone' => $model->phone])->one();
            if ($user) {
                $model->user_id = $user->id;
            }
            $model->description = ltrim($model->description);
            $model->time = strtotime($model->time);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->time = date('Y-m-d\TH:i', $model->time);
        $request = Yii::$app->request->post();
        if ($model->load($request)) {
            $user = User::find()->where(['phone' => $model->phone])->one();
            if ($user) {
                $model->user_id = $user->id;
            }
            $model->time = strtotime($model->time);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSetView()
    {
        $request = $_GET;
        $type = $request['type'];
        $session = Yii::$app->session;
        if ($type == 'grid') {
            $session->remove('view');
        } else {
            $session->set('view', 'grid');
        }
        return true;
    }

    public function actionSetUrl()
    {
        $request = $_GET;
        $params = json_decode($request['params'], true);
        $params['AppointmentSearch']['time_start'] = strtotime($request['time_start']);
        $params['AppointmentSearch']['time_end'] = strtotime($request['time_end']);
        $url = Url::to(['index']);
        $final = $url . "?" . http_build_query($params);
        return $final;
    }


    public function actionUpdatestatus($id)
    {
        $model = Appointment::findOne($id);
        $beginOfDay = strtotime(date('d-m-Y 00:00:00',$model->time));
        if(time() < $beginOfDay){
            return \yii\helpers\Json::encode(array('code' => 1));
        }
        $status = $model->status;
        if ($status == 1) {
            $model->status = 0;
        } else {
            $model->status = 1;
        }
        if ($model->save()) {
            return \yii\helpers\Json::encode(array(
                'code' => 200,
                'html' => '<i class="fa fa-times" aria-hidden="true"></i>',
                'title' => Yii::t('app', 'click_to_on'),
                'link' => Url::to(['updatestatus', 'id' => $id])
            ));
        } else {
            return \yii\helpers\Json::encode(array('code' => 400));
        }
    }

    public function actionViewOne(){
        return true;
    }

    public function actionGetValue($month,$year){
        if(!$month){
            $month = date('m',time());
        }
        if(!$year){
            $year = date('Y',time());
        }
        $time = $year.'-'.$month.'-'.'1';
        $time_current = strtotime($time);
        $firstDay = strtotime(date('Y-m-01',$time_current));
        $lastDay = strtotime(date('Y-m-t',$time_current));
        $total_day = date('d', $lastDay);
        $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($firstDay)->format('Y-m-d 00:00:00'))->getTimestamp();
        $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($lastDay)->format('Y-m-d 23:59:59'))->getTimestamp();
        $model = Appointment::find()->where(['status_delete' => 0])->andFilterWhere(['>', 'time', $beginOfDay])->andFilterWhere(['<', 'time', $endOfDay])->joinWith(['branch', 'userAdmin', 'productCategory'])->orderBy('time DESC')->all();
        $list = [];
        if ($model) {
            foreach ($model as $value) {
                $list[] = [
                    'title' => date('H:i', $value->time) . ' - ' . $value->name,
                    'start' => date('Y-m-d H', $value->time),
                    'end' => date('Y-m-d H', $value->time + 3600),
                    'className' => $value->status == 0 ? 'chuaden' : 'daden',
                    'cs_medical' => $value->medical_record_id ? $value->medical_record_id : '',
                    'cs_time' => date('H:i', $value->time),
                    'cs_branch' => $value->branch->name,
                    'cs_name' => $value->name,
                    'cs_phone' => '0'.(int)$value->phone.'',
                    'cs_doctor' => isset($value->userAdmin->fullname) && $value->userAdmin->fullname ? $value->userAdmin->fullname : '',
                    'cs_note' => $value->description,
                    'cs_status' => $value->status == 0 ? 'Chưa đến' : 'Đã đến',
                    'cs_category' => isset($value->productCategory->name) && $value->productCategory->name ? $value->productCategory->name : '',
                ];
            }
        }
        return json_encode($list);
    }


    protected function findModel($id)
    {
        if (($model = Appointment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
