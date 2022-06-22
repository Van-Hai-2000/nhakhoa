<?php

namespace backend\modules\gcacoin\controllers;

use common\models\gcacoin\Config;
use common\models\gcacoin\PhoneOtp;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class GcacoinController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
       $model = Config::find()->all();
       return $this->render('index',['model' => $model]);
    }

    public function actionPhoneotp()
    {
        $model = PhoneOtp::getModel();
        if (isset($_POST['phone']) && $_POST['phone']) {
            \Yii::$app->session->open();
            if(isset($_SESSION['OTP_SUCCESS']) && $_SESSION['OTP_SUCCESS']) {
                unset($_SESSION['OTP_SUCCESS']);
                $model->phone = $_POST['phone'];
                if($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Đã lưu');
                } else {
                    Yii::$app->session->setFlash('error', 'Lưu không thành công');
                }
            }
        }
        return $this->render('phoneotp',['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
