<?php

namespace backend\modules\menu\controllers;

use Yii;
use common\models\menu\Menu;
use common\models\menu\MenuGroup;
use common\models\menu\search\MenuSearch;
use common\components\UploadLib;
use common\components\ClaHost;
use common\components\ClaGenerate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller {

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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        /**/
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Menu();
        //
        $group_id = Yii::$app->request->get('gid', 0);
        if (!$group_id) {
            throw new \yii\web\BadRequestHttpException;
        }
        $model_group = MenuGroup::findOne($group_id);
        if ($model_group === NULL) {
            throw new \yii\web\NotFoundHttpException;
        }
        //
        $model->group_id = $group_id;
        //
        $model->linkto = Menu::LINKTO_INNER; // Default trong website
        $model->order = 0; // Default thứ tự
        if ($model->load(Yii::$app->request->post())) {
            $model->name_en = ($model->name_en) ? $model->name_en: $model->name;
            //
            if ($model->linkto == Menu::LINKTO_OUTER) {
                if ($model->link == '') {
                    $model->addError('link', 'Trang đích không được để trống');
                }
            } else {
                $data_info = json_decode($model->values, true);
                $linkinfo = Menu::getMenuLinkInfo($data_info);
                if (!$linkinfo) {
                    throw new \yii\web\BadRequestHttpException;
                }
                $model->attributes = $linkinfo;
            }
            //
            if ($model->avatar) {
                $avatar = Yii::$app->session[$model->avatar];
                if ($avatar) {
                    $model->avatar_path = $avatar['baseUrl'];
                    $model->avatar_name = $avatar['name'];
                }
                unset(Yii::$app->session[$model->avatar]);
            }

            if (!$model->hasErrors() && $model->validate()) {
                if ($model->save()) {
                    $key_cache_menu = \common\components\ClaLid::KEY_MENU . $model->group_id;
                    \Yii::$app->cache->delete($key_cache_menu);
                    return $this->redirect(['menu-group/index']);
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->name_en = ($model->name_en) ? $model->name_en: $model->name;
            //
            if ($model->linkto == Menu::LINKTO_OUTER) {
                if ($model->link == '') {
                    $model->addError('link', 'Trang đích không được để trống');
                }
            } else {
                $data_info = json_decode($model->values, true);
                $linkinfo = Menu::getMenuLinkInfo($data_info);
                if (!$linkinfo) {
                    throw new \yii\web\BadRequestHttpException;
                }
                $model->attributes = $linkinfo;
            }
            //
            if ($model->avatar) {
                $avatar = Yii::$app->session[$model->avatar];
                if ($avatar) {
                    $model->avatar_path = $avatar['baseUrl'];
                    $model->avatar_name = $avatar['name'];
                }
                unset(Yii::$app->session[$model->avatar]);
            }
            if (!$model->hasErrors() && $model->validate()) {
                if ($model->save()) {
                    $key_cache_menu = \common\components\ClaLid::KEY_MENU . $model->group_id;
                    \Yii::$app->cache->delete($key_cache_menu);
                    return $this->redirect(['menu-group/index']);
                }
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['menu-group/index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Menu::findOne($id)) !== null) {
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
            $up->setPath(array('menu', date('Y_m_d', time())));
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
