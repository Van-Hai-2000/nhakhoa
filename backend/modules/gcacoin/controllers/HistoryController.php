<?php

namespace backend\modules\gcacoin\controllers;

use common\models\gcacoin\GcaCoinHistory;
use common\models\gcacoin\search\GcaCoinHistorySearch;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class HistoryController extends Controller {

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
     * Lists all gca_coin_history models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new GcaCoinHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = GcaCoinHistory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
