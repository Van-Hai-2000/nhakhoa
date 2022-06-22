<?php

namespace backend\modules\qa\controllers;

use Yii;
use common\models\qa\QA;
use common\models\qa\search\QASearch;
use common\components\UploadLib;
use common\components\ClaHost;
use common\components\ClaGenerate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QAController implements the CRUD actions for QA model.
 */
class QaController extends Controller {

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

    /**
     * Lists all QA models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new QASearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QA model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new QA model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new QA();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->publicdate && $model->publicdate != '' && (int) strtotime($model->publicdate)) {
                $model->publicdate = (int) strtotime($model->publicdate);
            } else {
                $model->publicdate = time();
            }
            // upload avatar
            if ($model->avatar) {
                $avatar = Yii::$app->session[$model->avatar];
                if ($avatar) {
                    $model->avatar_path = $avatar['baseUrl'];
                    $model->avatar_name = $avatar['name'];
                }
                unset(Yii::$app->session[$model->avatar]);
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        $model->publicdate = $model->publicdate ? date('d/m/Y H:i', $model->publicdate) : date('d/m/Y H:i', time());
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing QA model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->publicdate && $model->publicdate != '' && (int) strtotime($model->publicdate)) {
                $model->publicdate = (int) strtotime($model->publicdate);
            } else {
                $model->publicdate = time();
            }
            // upload avatar
            if ($model->avatar) {
                $avatar = Yii::$app->session[$model->avatar];
                if ($avatar) {
                    $model->avatar_path = $avatar['baseUrl'];
                    $model->avatar_name = $avatar['name'];
                }
                unset(Yii::$app->session[$model->avatar]);
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        $model->publicdate = $model->publicdate ? date('d/m/Y H:i', $model->publicdate) : date('d/m/Y H:i', time());
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing QA model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the QA model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return QA the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = QA::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * upload file
     */
    public function actionUploadfile() {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            if ($file['size'] > 1024 * 1000) {
                Yii::$app->end();
            }
            $up = new UploadLib($file);
            $up->setPath(array('qa', date('Y_m_d', time())));
            $up->uploadImage();
            $return = array();
            $response = $up->getResponse(true);
            $return = array('status' => $up->getStatus(), 'data' => $response, 'host' => ClaHost::getImageHost(), 'size' => '');
            if ($up->getStatus() == '200') {
                $keycode = ClaGenerate::getUniqueCode();
                $return['data']['realurl'] = ClaHost::getImageHost() . $response['baseUrl'] . 's100_100/' . $response['name'];
                $return['data']['avatar'] = $keycode;
                Yii::$app->session[$keycode] = $response;
            }
            echo json_encode($return);
            Yii::$app->end();
        }
        //
    }

}
