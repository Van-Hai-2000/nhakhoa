<?php

namespace backend\modules\withdraw\controllers;

use Yii;
use common\models\SaleV;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * SiteinfoController implements the CRUD actions for BankAdmin model.
 */
class SiteinfoController extends Controller
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
        $model = SaleV::find()->one();
        $model->scenario = 'affiliate';
        if ($model->load(Yii::$app->request->post())) {
            $model->time_start = strtotime($model->time_start);
            $model->time_end = strtotime($model->time_end);
            $model->user_admin = Yii::$app->user->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Cập nhật thành công');
            }
        }
        $model->time_start = $model->time_start ? date('d-m-Y H:i', $model->time_start) : '';
        $model->time_end = $model->time_end ? date('d-m-Y H:i', $model->time_end) : '';
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionConfig()
    {
        $model = \common\models\gcacoin\Config::getConfig();
        // $model->scenario = 'affiliate';
        if ($model->load(Yii::$app->request->post())) {
            $model->user_admin = Yii::$app->user->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Cập nhật thành công');
            } else {
                print_r($model->errors);
                die();
            }
        }
        return $this->render('config', [
            'model' => $model,
        ]);
    }
}
