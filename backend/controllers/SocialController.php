<?php

namespace backend\controllers;

use Yii;
use common\models\Social;
use common\models\search\SocialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SocialController implements the CRUD actions for Social model.
 */
class SocialController extends Controller {

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
     * Lists all Social models.
     * @return mixed
     */
    public function actionIndex() {
        $model = $this->findModel(\common\models\Siteinfo::ROOT_SITE_ID);
        if (!$model) {
            $model = new Social();
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                // $key_cache_social = \common\components\ClaLid::KEY_SOCIAL_INFO;
                // \Yii::$app->cache->delete($key_cache_social);
                return $this->redirect(['index']);
            } else {
                echo "<pre>";
                print_r($model->getErrors());
                echo "</pre>";
                die();
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays a single Social model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Social model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Social();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Social model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Social model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Social model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Social the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Social::findOne($id)) !== null) {
            return $model;
        } else {
            $model = new Social();
        }
    }

}
