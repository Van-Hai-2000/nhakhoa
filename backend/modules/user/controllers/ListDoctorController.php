<?php

namespace backend\modules\user\controllers;

use backend\models\search\UserAdminSearch;
use backend\models\UserAdmin;
use common\models\user\MedicalRecordItem;
use common\models\user\MedicalRecordItemChild;
use Yii;
use common\models\medical_record\Factory;
use common\models\medical_record\FactorySearch;
use yii\base\BaseObject;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FactoryController implements the CRUD actions for Factory model.
 */
class ListDoctorController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter ::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Factory models.
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

    public function actionView($id)
    {
        $record_item = (new Query())->select('*')->from('medical_record_item_child')->where(['doctor_id'=>$id])->all();

        $thuthuat = (new Query())->select('mdi.*,mdc.doctor_id') ->from('medical_record_item AS mdi')->leftJoin(['mdc' => 'medical_record_item_child'], '[[mdc.medical_record_item_id]]=[[mdi.id]]')->where(['mdc.doctor_id'=>$id])->all();


        return $this->render('view', [
            'thuthuat'=>$thuthuat,
            'record_item'=>$record_item,
        ]);

    }

    /**
     * Displays a single Factory model.
     * @param integer $id
     * @return mixed
     */

}
