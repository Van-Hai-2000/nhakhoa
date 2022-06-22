<?php

namespace backend\modules\banner\controllers;

use Yii;
use common\models\banner\Banner;
use common\models\banner\search\BannerSearch;
use common\components\UploadLib;
use common\components\ClaHost;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends Controller {

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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new BannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banner model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Banner();
        $model->order = 0;
        if ($model->load(Yii::$app->request->post())) {
            $file = $_FILES['src'];
            if ($file && $file['name']) {
                //
                $model->src = 'true';
                $extensions = Banner::allowExtensions();
                //
                if (!isset($extensions[$file['type']])) {
                    $model->addError('src', 'Banner không đúng định dạng');
                }
            }
            if (!$model->getErrors()) {
                $up = new UploadLib($file);
                $up->setPath(array('banners'));
                $up->setForceSize(array((int) $model->width, (int) $model->height));
                $up->uploadFile();
                $response = $up->getResponse(true);
                //
                if ($up->getStatus() == '200') {
                    $model->src = $response['baseUrl'] . $response['name'];
                } else {
                    $model->src = '';
                }
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $file = $_FILES['src'];
            if ($file && $file['name']) {
                //
                $model->src = 'true';
                $extensions = Banner::allowExtensions();
                //
                if (!isset($extensions[$file['type']])) {
                    $model->addError('src', 'Banner không đúng định dạng');
                }
            }
            if (!$model->getErrors()) {
                $up = new UploadLib($file);
                $up->setPath(array('banners'));
                $up->setForceSize(array((int) $model->width, (int) $model->height));
                $up->uploadFile();
                $response = $up->getResponse(true);
                //
                if ($up->getStatus() == '200') {
                    $model->src = $response['baseUrl'] . $response['name'];
                }
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Banner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
