<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class ManaController extends Controller
{

    public function actionIndex()
    {
        Yii::$app->cache->flush();
        return $this->render('index');
    }

}
