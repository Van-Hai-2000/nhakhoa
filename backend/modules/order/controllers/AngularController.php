<?php

namespace backend\modules\order\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * AngularController implements the CRUD actions for Angular model.
 */
class AngularController extends Controller {

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
     * Lists all Angular models.
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('index', [
        ]);
    }

}
