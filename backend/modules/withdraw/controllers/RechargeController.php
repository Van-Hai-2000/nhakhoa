<?php

/**
 * Created by PhpStorm.
 * User: duclt
 * Date: 12/19/2018
 * Time: 5:09 PM
 */

namespace backend\modules\withdraw\controllers;


use common\models\gcacoin\Gcacoin;
use common\models\gcacoin\PhoneOtp;
use common\models\gcacoin\Recharge;
use common\models\gcacoin\RechargeImages;
use frontend\models\User;
use backend\models\UserAdmin;
use frontend\models\search\UserSearch;
use yii\web\Controller;
use Yii;

class RechargeController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $user = User::findOne($id);
        $model = new Recharge();
        $images = [];
        return $this->render('update', ['user' => $user, 'model' => $model, 'images' => $images]);
    }

    public function actionConfirm()
    {
        $post = Yii::$app->request->post();
        $data = isset($post['data']) ? $post['data'] : array();
        $user_id = $data['user_id'];
        $otp = isset($post['otp']) ? $post['otp'] : array();
        $phone = PhoneOtp::find()->one()->phone;
        $admin = UserAdmin::findOne(Yii::$app->user->id);
        $user =  User::findOne($user_id);
        if ($admin && $user && isset($data) && $data) {
            if ($admin->checkOtp($otp)) {
                $coin = $data['value'];
                $model = Gcacoin::getModel($user_id);
                $fisrtcoin = $model->getCoin();
                if ($model->addCoin($coin) && $model->save(false)) {
                    $images = $data['images'];
                    $recharge = new Recharge();
                    $recharge->user_id = $user_id;
                    $recharge->admin_id = Yii::$app->user->id;
                    $recharge->value = $coin;
                    $tl = 'Nạp V thành công.';
                    if ($recharge->save(false)) {
                        $this->SaveImages($images, $recharge->id);
                        $return = [
                            'success' => true,
                            'message' => $tl
                        ];
                    } else {
                        $return = [
                            'success' => false,
                            'errors' => 'Lưu lỗi',
                        ];
                    }
                    $history = new \common\models\gcacoin\GcaCoinHistory();
                    $history->user_id = $model->user_id;
                    $history->type = 'ADD_COIN_CK';
                    $history->data = 'Xác nhận nạp thành công ' . formatMoney($coin) . ' V từ BQT.';
                    $history->gca_coin = $coin;
                    $history->first_coin = $fisrtcoin;
                    $history->last_coin = $model->getCoin();
                    $history->save(false);
                    //
                    $text = 'Bạn nhận được <b style="color: green"> ' . formatMoney($history->gca_coin) . '</b> V chuyển từ BQT. Số dư hiện tại: <b style="color: green">' . number_format($history->last_coin, 0, ',', '.') . '</b> V';
                    if ($user->email) {
                        \common\models\mail\SendEmail::sendMail([
                            'email' => $user->email,
                            'title' => $tl,
                            'content' => $text
                        ]);
                    }
                    $noti = new \common\models\notifications\Notifications();
                    $noti->title = $tl;
                    $noti->description = $text;
                    $noti->link = Yii::$app->urlManagerFrontEnd->createUrl(['/site/router-url', 'url' => '/management/gcacoin/index']);
                    $noti->type = 3;
                    $noti->recipient_id = $history->user_id;
                    $noti->unread = \common\components\ClaLid::STATUS_ACTIVED;
                    $noti->save();
                    Yii::$app->session->setFlash('success', $tl);
                } else {
                    $return = [
                        'success' => false,
                        'errors' => 'Lỗi cộng tiền.',
                    ];
                }
            } else {
                $return = [
                    'success' => false,
                    'errors' => $admin->_error_opt
                ];
            }
        } else {
            $return = [
                'success' => false,
                'errors' => 'Not request'
            ];
        }
        return json_encode($return);
    }

    public function actionCheckRecharge()
    {
        $post = Yii::$app->request->post();
        $user_id = isset($post['user_id']) ? $post['user_id'] : '';
        $value = isset($post['value']) ? $post['value'] : '';
        $images[] = isset($post['images']) ? $post['images'] : '';
        $user = User::findOne($user_id);
        $admin = UserAdmin::findOne(Yii::$app->user->id);
        if ($admin && $user && isset($value) && isset($images)) {
            $return = $admin->createInfoOtpBackend($post);
        } else {
            $return = [
                'success' => false,
                'errors' => 'Dữ liệu gửi lên không hợp lệ.'
            ];
        }
        return json_encode($return);
    }

    function SaveImages($newimage, $id)
    {
        $countimage = $newimage ? count($newimage) : 0;
        if ($newimage && $countimage > 0) {
            foreach ($newimage as $image_code) {
                $imgtem = \common\models\media\ImagesTemp::findOne($image_code);
                if ($imgtem) {
                    $wdimg = new RechargeImages();
                    $wdimg->attributes = $imgtem->attributes;
                    $wdimg->recharge_id = $id;
                    if ($wdimg->save()) {
                        $imgtem->delete();
                    }
                }
            }
            return true;
        }
        return false;
    }
}
