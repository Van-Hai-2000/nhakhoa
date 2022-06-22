<?php

namespace backend\modules\notifications\controllers;

use frontend\models\User;
use Yii;
use common\models\notifications\Notifications;
use common\models\notifications\search\NotificationsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NotificationsController implements the CRUD actions for Notifications model.
 */
class NotificationsController extends Controller
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
     * Lists all Notifications models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notifications model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Notifications model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Notifications();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Notifications::sendNotification($model->attributes);
                Yii::$app->session->setFlash('Thông báo gửi thành công.');
                return $this->redirect(['index']);
            }else{
                print_r('<pre>');
                print_r($model->getErrors());
                die;
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Notifications model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Notifications model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Notifications the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notifications::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSelectUs($type)
    {
        switch ($type) {
            case Notifications::TYPE_USER_ALL:
                $users = User::find()->where(['status' => User::STATUS_ACTIVE])->asArray()->all();
                break;
            case Notifications::TYPE_USER_SHOP:
                $users = User::find()->where(['status' => User::STATUS_ACTIVE, 'type' => User::TYPE_DOANH_NGHIEP])->asArray()->all();
                break;
            case Notifications::TYPE_USER_THO:
                $users = User::find()->where(['status' => User::STATUS_ACTIVE, 'type' => User::TYPE_THO])->asArray()->all();
                break;
            case Notifications::TYPE_USER_NORMAL:
                $users = User::find()->where(['status' => User::STATUS_ACTIVE, 'type' => User::TYPE_CA_NHAN])->asArray()->all();
                break;
            default:
                $users = User::find()->where(['status' => User::STATUS_ACTIVE])->asArray()->all();
                break;
        }
        return $this->renderPartial('select_user', ['users' => $users]);
    }
}
