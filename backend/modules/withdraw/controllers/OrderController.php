<?php

namespace backend\modules\withdraw\controllers;

use Yii;
use common\models\order\Order;
use common\models\order\OrderShop;
use common\models\order\search\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\User;
use backend\models\UserAdmin;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $_GET['OrderSearch']['type_payment'] = 1;
        $_GET['OrderSearch']['payment_status'] = 1;
        $_GET['OrderSearch']['payment_method'] = \common\components\payments\ClaPayment::PAYMENT_METHOD_CK;
        \common\models\NotificationAdmin::removeNotifaction('order');
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUpdate($id)
    {
        $order = $this->findModel($id);
        $user = \frontend\models\User::findOne($order->user_id);
        $model = new \common\models\gcacoin\Recharge();
        $images = [];
        return $this->render('update', ['user' => $user, 'model' => $model, 'images' => $images, 'order' => $order]);
    }
    public function actionDelete($id)
    {
        $order = $this->findModel($id);
        if ($order->delete()) {
            OrderShop::deleteByOrder($id);
            Yii::$app->session->setFlash('success', 'Xóa thành công.');
            return $this->redirect(['index']);
        }
        Yii::$app->session->setFlash('error', 'Không thể xóa.');
        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = Order::find()->where(['id' => $id, 'type_payment' => 1, 'payment_status' => 1, 'payment_method' =>  \common\components\payments\ClaPayment::PAYMENT_METHOD_CK])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCheckRecharge()
    {
        $post = Yii::$app->request->post();
        $order = $this->findModel($post['order_id']);
        $images[] = isset($post['images']) ? $post['images'] : '';
        $user = User::findOne($order->user_id);
        $admin = UserAdmin::findOne(Yii::$app->user->id);
        if ($order && $admin && $user && isset($images)) {
            $return = $admin->createInfoOtpBackend($post);
        } else {
            $return = [
                'success' => false,
                'errors' => 'Dữ liệu gửi lên không hợp lệ.'
            ];
        }
        return json_encode($return);
    }

    public function actionConfirm()
    {
        $post = Yii::$app->request->post();
        $data = isset($post['data']) ? $post['data'] : array();
        $order_id = $data['order_id'];
        $order = $this->findModel($order_id);
        $user_id = $order->user_id;
        $otp = isset($post['otp']) ? $post['otp'] : array();
        $admin = UserAdmin::findOne(Yii::$app->user->id);
        $user =  User::findOne($user_id);
        if ($admin && $user && isset($data) && $data) {
            if ($admin->checkOtp($otp)) {
                $coin = \common\models\gcacoin\Gcacoin::getCoinToMoney($order->order_total);
                $model = \common\models\gcacoin\Gcacoin::getModel($user_id);
                if ($order->to_sale == 1) {
                    //khuyễn mãi
                    $coin = $coin + ($coin * $order->percent_sale / 100);
                    $fisrtcoin = $model->getCoinSale();
                    if ($model->addCoinSale($coin) && $model->save(false)) {
                        $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
                        $order->save(false);
                        $recharge = new \common\models\gcacoin\Recharge();
                        $recharge->user_id = $user_id;
                        $recharge->admin_id = Yii::$app->user->id;
                        $recharge->value = $coin;
                        $recharge->order_id = $order->id;
                        $tl = 'Nạp ' . __VOUCHER_SALE . ' thành công.';

                        $recharge->save(false);
                        $history = new \common\models\gcacoin\GcaCoinHistory();
                        $history->user_id = $model->user_id;
                        $history->type = 'ADD_COIN_CK';
                        $history->data = 'Xác nhận nạp thành công ' . formatMoney($coin) . ' ' . __VOUCHER_SALE . ' bằng hình thức chuyển khoản.';
                        $history->gca_coin = $coin;
                        $history->first_coin = $fisrtcoin;
                        $history->last_coin = $model->getCoinSale();
                        $history->type_coin = \common\models\gcacoin\GcaCoinHistory::TYPE_V_SALE;
                        $history->save(false);
                        $text = 'Bạn nhận được <b style="color: green"> ' . formatMoney($history->gca_coin) . '</b> ' . __VOUCHER_SALE . ' bằng hình thức chuyển khoản. Số dư hiện tại: <b style="color: green">' . number_format($history->last_coin, 0, ',', '.') . '</b> ' . __VOUCHER_SALE;
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
                        $return = [
                            'success' => true,
                            'message' => $tl
                        ];
                    }
                } else {
                    //Thường
                    $fisrtcoin = $model->getCoin();
                    if ($model->addCoin($coin) && $model->save(false)) {
                        $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
                        $order->save(false);
                        $recharge = new \common\models\gcacoin\Recharge();
                        $recharge->user_id = $user_id;
                        $recharge->admin_id = Yii::$app->user->id;
                        $recharge->value = $coin;
                        $recharge->order_id = $order->id;
                        $tl = 'Nạp ' . __VOUCHER . ' thành công.';
                        $recharge->save(false);
                        $history = new \common\models\gcacoin\GcaCoinHistory();
                        $history->user_id = $model->user_id;
                        $history->type = 'ADD_COIN_CK';
                        $history->data = 'Xác nhận nạp thành công ' . formatMoney($coin) . ' ' . __VOUCHER . ' V bằng hình thức chuyển khoản.';
                        $history->gca_coin = $coin;
                        $history->first_coin = $fisrtcoin;
                        $history->last_coin = $model->getCoin();
                        $history->save(false);
                        //
                        $text = 'Bạn nhận được <b style="color: green"> ' . formatMoney($history->gca_coin) . '</b> ' . __VOUCHER . ' bằng hình thức chuyển khoản. Số dư hiện tại: <b style="color: green">' . number_format($history->last_coin, 0, ',', '.') . '</b> ' . __VOUCHER;
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
                        $return = [
                            'success' => true,
                            'message' => $tl
                        ];
                    }
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

    public function actionCancer()
    {
        $post = Yii::$app->request->post();
        $data = isset($post['data']) ? $post['data'] : array();
        $order_id = $data['order_id'];
        $order = $this->findModel($order_id);
        $user_id = $order->user_id;
        $content = isset($post['content']) ? $post['content'] : 'Không nhận được tiền chuyển khoản';
        $user =  User::findOne($user_id);
        if ($user && isset($data) && $data) {
            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_CANCEL;
            $order->save(false);
            $text_v = $order->to_sale ? __VOUCHER_SALE : __VOUCHER;
            $tl = "Hủy bỏ yêu cầu nạp {$text_v} qua chuyển khoản";
            $text = "Yêu cầu nạp {$text_v} qua chuyển khoản với KEY: " . $order->key . " đã bị hủy bỏ với lý do: <b>{$content}</b>";
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
            $noti->link = '';
            $noti->type = 3;
            $noti->recipient_id = $order->user_id;
            $noti->unread = \common\components\ClaLid::STATUS_ACTIVED;
            $noti->save();
            Yii::$app->session->setFlash('success', 'Hủy yêu cầu thành công.');
            $return = [
                'success' => true,
                'message' => 'Hủy yêu cầu thành công.'
            ];
        } else {
            $return = [
                'success' => false,
                'errors' => 'Not request'
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
                    $wdimg = new \common\models\gcacoin\RechargeImages();
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
