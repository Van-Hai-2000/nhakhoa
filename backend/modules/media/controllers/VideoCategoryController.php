<?php

namespace backend\modules\media\controllers;

use Yii;
use common\models\media\VideoCategory;
use common\models\media\search\VideoCategorySearch;
use common\components\UploadLib;
use common\components\ClaHost;
use common\components\ClaGenerate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ClaYoutube;

/**
 * VideoCategoryController implements the CRUD actions for VideoCategory model.
 */
class VideoCategoryController extends Controller {

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
     * Lists all VideoCategory models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new VideoCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VideoCategory model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VideoCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new VideoCategory();
        if ($model->load(Yii::$app->request->post())) {
            
            $youtube = new ClaYoutube($model->link);
            if (!$youtube->isLink) {
                $model->addError('link', 'Link video không đúng định dạng');
            } else {
                $yinfo = $youtube->getEmebed();
                if ($yinfo) {
                    $model->embed = $yinfo['embed_link'];
                    $model->height = $yinfo['height'];
                    $model->width = $yinfo['width'];
                }
            }
            
            if ($model->parent) {
                $category_parent = $this->findModel($model->parent);
                $model->level = $category_parent->level++;
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
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing VideoCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $youtube = new ClaYoutube($model->link);
            if (!$youtube->isLink) {
                $model->addError('link', 'Link video không đúng định dạng');
            } else {
                $yinfo = $youtube->getEmebed();
                if ($yinfo) {
                    $model->embed = $yinfo['embed_link'];
                    $model->height = $yinfo['height'];
                    $model->width = $yinfo['width'];
                }
            }

            if ($model->parent) {
                $category_parent = $this->findModel($model->parent);
                $model->level = $category_parent->level + 1;
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
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing VideoCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VideoCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return VideoCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = VideoCategory::findOne($id)) !== null) {
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
            $up->setPath(array('video_category', date('Y_m_d', time())));
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
