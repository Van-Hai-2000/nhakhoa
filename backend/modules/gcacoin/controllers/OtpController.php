<?php

/**
 * Created by PhpStorm.
 * User: duclt
 * Date: 11/24/2018
 * Time: 12:13 PM
 */

namespace backend\modules\gcacoin\controllers;

use common\components\ClaQrCode;
use common\models\gcacoin\PhoneOtp;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

class OtpController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-otp' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionGetOtp()
    {
        $phone = PhoneOtp::getModel();
        $phone = isset($phone->phone) ? $phone->phone : '';
        if ($phone) {
            $get_otp = ClaQrCode::getOtpCheckAll($phone);
            if (isset($get_otp['success']) && $get_otp['success']) {
                $data = ($get_otp['data']);
                if ($data['success']) {
                    return $this->renderAjax('form-check');
                } else {
                    return "<script>alert('" . $data['message'] . "')</script>";
                }
            } else {
                return "<script>alert('" . $get_otp['error'] . "')</script>";
            }
        }
        return "<script>alert('Vui lòng cấu hình số điện thoại nhập OTP');</script>";
    }

    public function actionCheckOtp($otp)
    {
        $phone = PhoneOtp::getModel();
        $phone = isset($phone->phone) ? $phone->phone : '';
        if ($phone) {
            $check_otp = ClaQrCode::checkOtpCheckAll($phone, $otp);
            if (isset($check_otp['success']) && $check_otp['success']) {
                \Yii::$app->session->open();
                $_SESSION['OTP_SUCCESS'] = true;
                $update = ClaQrCode::updateOtp($phone, $otp);
                return "<script>$('#otp-form').submit();</script>";
            } else {
                return $this->renderAjax('form-check', [
                    'error' => isset($check_otp['error']) ? $check_otp['error'] : 'Nhập sai opt'
                ]);
            }
        }
        return "<script>alert('Vui lòng cấu hình số điện thoại nhập OTP');</script>";
    }

    public function actionCheckPass2()
    {
        $otp = Yii::$app->request->post('otp');
        $user =  Yii::$app->user->identity;
        if ($otp && $user->checkOtp($otp)) {
            return 'success';
        }
        return 'error';
    }
}
