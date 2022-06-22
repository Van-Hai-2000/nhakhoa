<?php

namespace backend\controllers;

use backend\modules\auth\models\Assignment;
use common\components\UploadLib;
use common\models\auth\AuthAssignment;
use common\models\banner\Banner;
use Yii;
use backend\models\UserAdmin;
use backend\models\search\UserAdminSearch;
use backend\models\SignupForm;
use yii\base\Exception;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserAdminController implements the CRUD actions for UserAdmin model.
 */
class UserAdminController extends Controller
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

    /**
     * Lists all UserAdmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserAdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDoctor()
    {
        $searchModel = new UserAdminSearch();
        $dataProvider = $searchModel->searchDoctor(Yii::$app->request->queryParams);

        return $this->render('doctor/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserAdmin model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserAdmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SignupForm();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {
            $file = $_FILES['src'];
            if ($file && $file['name']) {
                $model->src = 'true';
                $extensions = Banner::allowExtensions();
                if (!isset($extensions[$file['type']])) {
                    $model->addError('src', 'Ảnh không đúng định dạng');
                }
            }
            $up = new UploadLib($file);
            $up->setPath(array('user'));
            $up->uploadFile();
            $response = $up->getResponse(true);
            if ($up->getStatus() == '200') {
                $model->src = $response['baseUrl'] . $response['name'];
            } else {
                $model->src = '';
            }

            if ($user = $model->signup()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model_admin = $this->findModel($id);
        $model = new SignupForm();
        $model->attributes = $model_admin->attributes;
        if ($model->load(Yii::$app->request->post())) {
            $file = $_FILES['src'];
            if ($file && $file['name']) {
                $model->src = 'true';
                $extensions = Banner::allowExtensions();
                if (!isset($extensions[$file['type']])) {
                    $model->addError('src', 'Ảnh không đúng định dạng');
                }
            }
            $up = new UploadLib($file);
            $up->setPath(array('user'));
            $up->uploadFile();
            $response = $up->getResponse(true);
            if ($up->getStatus() == '200') {
                $model->src = $response['baseUrl'] . $response['name'];
            } else {
                $model->src = '';
            }


            $model_admin->attributes = $model->attributes;
            if ($model->password) {
                $model_admin->setPassword($model->password);
            }
            if ($model->password2) {
                $model_admin->setPassword2($model->password2);
            }
            $model_admin->generateAuthKey();
            if ($model_admin->save()) {
                Yii::$app->session->setFlash('success', 'Lưu thành công.');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionCreateDoctor()
    {
        $model = new SignupForm();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->redirect(['doctor']);
            }
        }

        return $this->render('doctor/create', [
            'model' => $model,
        ]);
    }

    public function actionUpdateDoctor($id)
    {
        $model_admin = $this->findModel($id);
        $model = new SignupForm();
        $model->attributes = $model_admin->attributes;
        if ($model->load(Yii::$app->request->post())) {
            $model_admin->attributes = $model->attributes;
            if ($model->password) {
                $model_admin->setPassword($model->password);
            }
            if ($model->password2) {
                $model_admin->setPassword2($model->password2);
            }
            $model_admin->generateAuthKey();
            if ($model_admin->save()) {
                Yii::$app->session->setFlash('success', 'Lưu thành công.');
                return $this->redirect(['doctor']);
            }
        }
        return $this->render('doctor/update', [
            'model' => $model,
        ]);
    }

    public function actionDeleteDoctor($id)
    {
        $model_admin = $this->findModel($id);
        $model_admin->status = 0;
        $model_admin->save();
        return $this->redirect(['doctor/index']);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserAdmin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //Cấp quyền cho tài khoản
    public function actionAuth($id)
    {
        $model = UserAdmin::findOne($id);

        $d = (new Query())->select('name, type')->from('auth_item')->where(['type' => 1])->andWhere(['<>', 'name', 'Admin'])->all();
        $pass_rule = (new Query())->select('item_name, user_id')->from('auth_assignment')->where(['user_id' => $id])->all();
        $name = [];
        $data = [];
        foreach ($d as $item) {
            $n = explode('-', $item['name']);
            !in_array($n[0], $name) ? $name[] = $n[0] : null;
        }
        foreach ($name as $item) {
            foreach ($d as $i) {
                $n = explode('-', $i['name']);
                if ($n[0] == $item) {
                    $data[$item][] = [
                        'id' => $i['name'],
                        'item' => isset($n[1]) && $n[1] ? $n[1] : $n[0],
                    ];
                }
            }
        }

        $post = Yii::$app->request->post();
        if (isset($post['auth_item']) && $post['auth_item']) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                Yii::$app
                    ->db
                    ->createCommand()
                    ->delete('auth_assignment', ['user_id' => $id])
                    ->execute();
                foreach ($post['auth_item'] as $value) {
                    $auth = new Assignment($id);
                    $response = $auth->assign([$value]);
                    if ($response != 0) {
                        Yii::$app->session->setFlash('success', 'Phân quyền thành công');
                    } else {
                        throw  new Exception('Phân quyền thất bại');
                    }
                }
                $transaction->commit();
                return $this->refresh();
            } catch (\Exception $e) {
                Yii::warning($e->getMessage());
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $response['errorsth'] = $e->getMessage();
                $transaction->rollBack();
                throw $e;
            }

        }

        return $this->render('auth', [
            'model' => $model,
            'rule' => isset($data) ? $data : null,
            'pass_rule' => isset($pass_rule) ? array_column($pass_rule, 'item_name') : null,
        ]);
    }
}
