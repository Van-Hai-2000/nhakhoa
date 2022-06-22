<?php

namespace backend\modules\media\controllers;

use Yii;
use common\models\media\Video;
use common\models\media\search\VideoSearch;
use common\components\UploadLib;
use common\components\ClaHost;
use common\components\ClaGenerate;
use common\components\ClaYoutube;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends Controller {

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
     * Lists all Video models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new VideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Video model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Video model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Video();

        if ($model->load(Yii::$app->request->post())) {

            // get enbed youtube
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

            // upload avatar
            if ($model->avatar) {
                $avatar = Yii::$app->session[$model->avatar];
                if ($avatar) {
                    $model->avatar_path = $avatar['baseUrl'];
                    $model->avatar_name = $avatar['name'];
                }
                unset(Yii::$app->session[$model->avatar]);
            } else if (isset($yinfo)) {
                $up = new UploadLib();
                $up->setPath(array('video'));
                $up->getFile(array(
                    'link' => $yinfo['thumbnail_url'],
                    'filetype' => UploadLib::UPLOAD_IMAGE,
                ));
                $response = $up->getResponse(true);
                if ($up->getStatus() == '200') {
                    $model->avatar_path = $response['baseUrl'];
                    $model->avatar_name = $response['name'];
                }
            }

            if ($model->save()) {
                // update price
                $prices_update = Yii::$app->request->post('VideoPriceUpdate');
                if (isset($prices_update) && $prices_update) {
                    foreach ($prices_update as $price_id => $price_update) {
                        if ($price_update['from_second'] && $price_update['to_second'] && $price_update['price']) {
                            $model_price_update = VideoPrice::findOne($price_id);
                            $model_price_update->attributes = $price_update;
                            $model_price_update->save();
                        }
                    }
                }
                // new price
                $prices = Yii::$app->request->post('VideoPrice');
                if (isset($prices) && $prices) {
                    foreach ($prices as $price) {
                        if ($price['from_second'] && $price['to_second'] && $price['price']) {
                            $model_price = new VideoPrice();
                            $model_price->attributes = $price;
                            $model_price->video_id = $model->id;
                            $model_price->save();
                        }
                    }
                }
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Video model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // get enbed youtube
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

            // upload avatar
            if ($model->avatar) {
                $avatar = Yii::$app->session[$model->avatar];
                if ($avatar) {
                    $model->avatar_path = $avatar['baseUrl'];
                    $model->avatar_name = $avatar['name'];
                }
                unset(Yii::$app->session[$model->avatar]);
            }
            //
            if ($model->save()) {
                // update price
                $prices_update = Yii::$app->request->post('VideoPriceUpdate');
                if (isset($prices_update) && $prices_update) {
                    foreach ($prices_update as $price_id => $price_update) {
                        if ($price_update['from_second'] && $price_update['to_second'] && $price_update['price']) {
                            $model_price_update = VideoPrice::findOne($price_id);
                            $model_price_update->attributes = $price_update;
                            $model_price_update->save();
                        }
                    }
                }
                // new price
                $prices = Yii::$app->request->post('VideoPrice');
                if (isset($prices) && $prices) {
                    foreach ($prices as $price) {
                        if ($price['from_second'] && $price['to_second'] && $price['price']) {
                            $model_price = new VideoPrice();
                            $model_price->attributes = $price;
                            $model_price->video_id = $model->id;
                            $model_price->save();
                        }
                    }
                }
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Video model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeletePrice() {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $price_id = Yii::$app->request->get('price_id', 0);
            if (isset($price_id) && $price_id) {
                $model = VideoPrice::findOne($price_id);
                $model->delete();
                return ['code' => 200];
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }

    /**
     * Finds the Video model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Video the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Video::findOne($id)) !== null) {
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
            $up->setPath(array('video', date('Y_m_d', time())));
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
