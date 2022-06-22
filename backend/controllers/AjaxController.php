<?php

namespace backend\controllers;

use backend\models\UserAdmin;
use common\models\District;
use common\models\Ward;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class AjaxController extends Controller
{
    public function actionGetUserAdmin()
    {
        $user_admin = UserAdmin::getUserIntroduce();
        return json_encode($user_admin);
    }

    public function actionGetDistrict()
    {
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $request = $_GET;
            $dt = District::dataFromProvinceId($request['province_id']);
            return json_encode($dt);
        } else {
            return false;
        }
    }

    public function actionGetWard()
    {
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $request = $_GET;
            $data = Ward::dataFromDistrictId($request['district_id']);
            return json_encode($data);
        } else {
            return false;
        }
    }

}
