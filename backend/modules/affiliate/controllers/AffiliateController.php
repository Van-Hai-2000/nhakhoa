<?php

namespace backend\modules\affiliate\controllers;

use Yii;
use common\models\affiliate\Affiliate;
use common\models\search\AffiliateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AffiliateController implements the CRUD actions for Affiliate model.
 */
class AffiliateController extends Controller
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
     * Lists all Affiliate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = $this->findModel();
        $user = Yii::$app->user->identity;
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time();
            $model->user_update = Yii::$app->user->id;
            if ($user->successOtp() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Lưu thành công.');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Finds the Affiliate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Affiliate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        if (($model = Affiliate::find()->one()) !== null) {
            return $model;
        } else {
            $model = new Affiliate();
            return $model;
        }
    }
}
