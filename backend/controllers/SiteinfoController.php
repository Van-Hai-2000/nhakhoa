<?php

namespace backend\controllers;

use Yii;
use common\models\Siteinfo;
use common\models\search\SiteinfoSearch;
use common\components\UploadLib;
use common\components\ClaHost;
use common\components\ClaGenerate;
use common\components\ClaLid;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SiteinfoController implements the CRUD actions for Siteinfo model.
 */
class SiteinfoController extends Controller {

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
     * Lists all Siteinfo models.
     * @return mixed
     */
    public function actionIndex() {
        $id = Siteinfo::ROOT_SITE_ID;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->isNewRecord) {
                $model->created_at = time();
            }
            $model->updated_at = time();
            // upload logo
            if ($model->logo) {
                $logo = Yii::$app->session[$model->logo];
                if ($logo) {
                    $model->logo = ClaHost::getImageHost() . $logo['baseUrl'] . $logo['name'];
                }
                unset(Yii::$app->session[$model->logo]);
            }
            // upload logo
            if ($model->footer_logo) {
                $logo = Yii::$app->session[$model->footer_logo];
                if ($logo) {
                    $model->footer_logo = ClaHost::getImageHost() . $logo['baseUrl'] . $logo['name'];
                }
                unset(Yii::$app->session[$model->footer_logo]);
            }
            // upload favicon
            if ($model->favicon) {
                $favicon = Yii::$app->session[$model->favicon];
                if ($favicon) {
                    $model->favicon = ClaHost::getImageHost() . $favicon['baseUrl'] . $favicon['name'];
                }
                unset(Yii::$app->session[$model->favicon]);
            }
            if ($model->save()) {
                Yii::$app->cache->flush();
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
//        $searchModel = new SiteinfoSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
//        ]);
    }

    /**
     * Displays a single Siteinfo model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Siteinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Siteinfo();

        if ($model->load(Yii::$app->request->post())) {
            // upload logo
            if ($model->logo) {
                $logo = Yii::$app->session[$model->logo];
                if ($logo) {
                    $model->logo = ClaHost::getImageHost() . $logo['baseUrl'] . $logo['name'];
                }
                unset(Yii::$app->session[$model->logo]);
            }
            // upload favicon
            if ($model->favicon) {
                $favicon = Yii::$app->session[$model->favicon];
                if ($favicon) {
                    $model->favicon = ClaHost::getImageHost() . $favicon['baseUrl'] . $favicon['name'];
                }
                unset(Yii::$app->session[$model->favicon]);
            }
            // upload logo footer
            if ($model->footer_logo) {
                $logo = Yii::$app->session[$model->footer_logo];
                if ($logo) {
                    $model->footer_logo = ClaHost::getImageHost() . $logo['baseUrl'] . $logo['name'];
                }
                unset(Yii::$app->session[$model->footer_logo]);
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
     * Updates an existing Siteinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // upload logo
            if ($model->logo) {
                $logo = Yii::$app->session[$model->logo];
                if ($logo) {
                    $model->logo = ClaHost::getImageHost() . $logo['baseUrl'] . $logo['name'];
                }
                unset(Yii::$app->session[$model->logo]);
            }
            // upload favicon
            if ($model->favicon) {
                $favicon = Yii::$app->session[$model->favicon];
                if ($favicon) {
                    $model->favicon = ClaHost::getImageHost() . $favicon['baseUrl'] . $favicon['name'];
                }
                unset(Yii::$app->session[$model->favicon]);
            }
            // upload logo
            if ($model->footer_logo) {
                $logo = Yii::$app->session[$model->footer_logo];
                if ($logo) {
                    $model->footer_logo = ClaHost::getImageHost() . $logo['baseUrl'] . $logo['name'];
                }
                unset(Yii::$app->session[$model->footer_logo]);
            }
            if ($model->save()) {
                \Yii::$app->cache->delete(\common\components\ClaLid::KEY_SITE_INFO);
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Siteinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Siteinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Siteinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Siteinfo::findOne($id)) !== null) {
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
            $up->setPath(array('siteinfo', date('Y_m_d', time())));
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
