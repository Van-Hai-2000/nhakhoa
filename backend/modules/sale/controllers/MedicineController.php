<?php

namespace backend\modules\sale\controllers;

use common\models\user\search\MedicalRecordItemMedicineSearch;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * MedicineController implements the CRUD actions for OperationSales model.
 */
class MedicineController extends Controller
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
     * Lists all OperationSales models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MedicalRecordItemMedicineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $url = Url::to(['excel']);
        $url = $url . "?" . http_build_query($_GET);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $_GET,
            'url' => $url,
        ]);
    }

    public function actionSetUrl()
    {
        $request = $_GET;
        $params = json_decode($request['params'], true);
        $params['MedicalRecordItemMedicineSearch']['time_start'] = strtotime($request['time_start']);
        $params['MedicalRecordItemMedicineSearch']['time_end'] = strtotime($request['time_end']);
        $url = Url::to(['index']);
        $final = $url . "?" . http_build_query($params);
        return $final;
    }
}
