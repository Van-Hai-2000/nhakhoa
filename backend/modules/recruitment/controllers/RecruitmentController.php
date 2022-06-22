<?php

namespace backend\modules\recruitment\controllers;

use Yii;
use common\models\recruitment\Recruitment;
use common\models\recruitment\search\RecruitmentSearch;
use common\models\recruitment\RecruitmentInfo;
use common\models\recruitment\Skill;
use common\components\UploadLib;
use common\components\ClaHost;
use common\components\ClaGenerate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RecruitmentController implements the CRUD actions for Recruitment model.
 */
class RecruitmentController extends Controller {

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
     * Lists all Recruitment models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RecruitmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Recruitment model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Recruitment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Recruitment();
        $model->currency = 2;
        $model_info = new RecruitmentInfo();

        $post = Yii::$app->request->post();
        if (isset($post['Recruitment']) && $post['Recruitment']) {
            // process skill
            $skill_array = [];
            if ($post['Recruitment']['skills']) {
                foreach ($post['Recruitment']['skills'] as $skill) {
                    if (is_numeric($skill)) {
                        $skill_array[] = $skill;
                    } else {
                        $model_skill = new Skill();
                        $model_skill->name = $skill;
                        $model_skill->created_at = time();
                        $model_skill->updated_at = time();
                        $model_skill->save();
                        $skill_array[] = $model_skill->id;
                    }
                }
            }
            $model->attributes = $post['Recruitment'];
            //Skill
            if (!empty($skill_array)) {
                $model->skills = implode(' ', $skill_array);
            }
            // xử lý data
            $model->category_id = $model->category_id ? implode(' ', $model->category_id) : '';
            $model->typeofworks = $model->typeofworks ? implode(' ', $model->typeofworks) : '';
            $model->locations = $model->locations ? implode(' ', $model->locations) : '';
            if ($model->publicdate && $model->publicdate != '' && (int) strtotime($model->publicdate)) {
                $model->publicdate = (int) strtotime($model->publicdate);
            } else {
                $model->publicdate = time();
            }
            if ($model->expiration_date && $model->expiration_date != '' && (int) strtotime($model->expiration_date)) {
                $model->expiration_date = (int) strtotime($model->expiration_date);
            } else {
                $model->expiration_date = time();
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
            $model->user_id = Yii::$app->user->getId();
            if ($model->save()) {
                // Lưu thông tin mô tả
                $model_info->attributes = $post['RecruitmentInfo'];
                $model_info->recruitment_id = $model->id;
                $model_info->save();
                //
                return $this->redirect(['index']);
            }
        }
        // xử lý data
        $model->skills = $model->skills ? explode(' ', $model->skills) : '';
        $model->category_id = $model->category_id ? explode(' ', $model->category_id) : '';
        $model->typeofworks = $model->typeofworks ? explode(' ', $model->typeofworks) : '';
        $model->locations = $model->locations ? explode(' ', $model->locations) : '';
        $model->publicdate = $model->publicdate ? date('d/m/Y H:i', $model->publicdate) : date('d/m/Y H:i', time());
        $model->expiration_date = $model->expiration_date ? date('d/m/Y H:i', $model->expiration_date) : date('d/m/Y H:i', time());
        return $this->render('create', [
                    'model' => $model,
                    'model_info' => $model_info
        ]);
    }

    /**
     * Updates an existing Recruitment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model_info = $this->findModelInfo($id);

        $post = Yii::$app->request->post();
        if (isset($post['Recruitment']) && $post['Recruitment']) {
            // process skill
            $skill_array = [];
            if ($post['Recruitment']['skills']) {
                foreach ($post['Recruitment']['skills'] as $skill) {
                    if (is_numeric($skill)) {
                        $skill_array[] = $skill;
                    } else {
                        $model_skill = new Skill();
                        $model_skill->name = $skill;
                        $model_skill->created_at = time();
                        $model_skill->updated_at = time();
                        $model_skill->save();
                        $skill_array[] = $model_skill->id;
                    }
                }
            }
            $model->attributes = $post['Recruitment'];
            //Skill
            if (!empty($skill_array)) {
                $model->skills = implode(' ', $skill_array);
            }
            // xử lý data
            $model->category_id = $model->category_id ? implode(' ', $model->category_id) : '';
            $model->typeofworks = $model->typeofworks ? implode(' ', $model->typeofworks) : '';
            $model->locations = $model->locations ? implode(' ', $model->locations) : '';
            if ($model->publicdate && $model->publicdate != '' && (int) strtotime($model->publicdate)) {
                $model->publicdate = (int) strtotime($model->publicdate);
            } else {
                $model->publicdate = time();
            }
            if ($model->expiration_date && $model->expiration_date != '' && (int) strtotime($model->expiration_date)) {
                $model->expiration_date = (int) strtotime($model->expiration_date);
            } else {
                $model->expiration_date = time();
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
                // Lưu thông tin mô tả
                $model_info->attributes = $post['RecruitmentInfo'];
                $model_info->recruitment_id = $model->id;
                $model_info->save();
                //
                return $this->redirect(['index']);
            }
        }
        // xử lý data
        $model->skills = $model->skills ? explode(' ', $model->skills) : '';
        $model->category_id = $model->category_id ? explode(' ', $model->category_id) : '';
        $model->typeofworks = $model->typeofworks ? explode(' ', $model->typeofworks) : '';
        $model->locations = $model->locations ? explode(' ', $model->locations) : '';
        $model->publicdate = $model->publicdate ? date('d/m/Y H:i', $model->publicdate) : date('d/m/Y H:i', time());
        $model->expiration_date = $model->expiration_date ? date('d/m/Y H:i', $model->expiration_date) : date('d/m/Y H:i', time());
        return $this->render('update', [
                    'model' => $model,
                    'model_info' => $model_info
        ]);
    }

    /**
     * Deletes an existing Recruitment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        if ($this->findModel($id)->delete()) {
            $this->findModelInfo($id)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Recruitment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Recruitment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Recruitment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelInfo($id) {
        if (($model = RecruitmentInfo::findOne($id)) !== null) {
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
            $up->setPath(array('recruitments', date('Y_m_d', time())));
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
