<?php
/**
 * Created by PhpStorm.
 * User: duclt
 * Date: 11/24/2018
 * Time: 11:48 AM
 */

namespace backend\modules\gcacoin\controllers;

use common\components\ClaQrCode;
use yii\web\Controller;
use Yii;

class CheckotpController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $check = ClaQrCode::getSession('check_otp');
        if (isset($check) && $check) {
            return true;
        } else {
            return $this->redirect(['/gcacoin/otp/otp']);
            return false;
        }
        return true;
    }
}