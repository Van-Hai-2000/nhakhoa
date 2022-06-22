<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/21/2018
 * Time: 5:50 PM
 */
namespace common\components;

use common\models\product\Product;
use Yii;
use yii\helpers\Url;
use common\models\Province;
use common\models\District;
use common\models\Ward;
use common\models\notifications\Notifications;

class ClaOrderQr
{
    public static function AddOrder($options= []) {
        $quantity = $options['data']['quantity'];
        $transport_type = $options['data']['tranport_type'];
        $transport_id = 0;
        $shipfee = $options['data']['shipfee'];
        $price = $options['price'] ? $options['price'] : 0;
        $product_id = $options['data']['id'];
        $id_adress_shop = (isset($options['data']['address_id_from']) && $options['data']['address_id_from']) ? $options['data']['address_id_from'] : false;
        $data_pr['district'] = $options['data']['district'];
        $data_pr['province'] = $options['data']['province'];
        $product = Product::findOne($product_id);
        $shop_id = $product->shop_id;
        $user_id = $options['user_id'];
        $shop = \common\models\shop\Shop::findOne($shop_id);
        $address = \common\models\user\UserAddress::find()->where(['user_id' => $user_id, 'isdefault' => \common\components\ClaLid::STATUS_ACTIVED])->one();
        $order = new \common\models\order\Order();
        $orderShop = new \common\models\order\OrderShop();
        //khoi tao order
        $order->key = \common\components\ClaGenerate::getUniqueCode();
        $order->order_total = $price;
        $order->user_id = $user_id;
        $order->email = $address['email'];
        $order->name = $address['name_contact'];
        $order->phone = $address['phone'];
        $order->address = $address['address'] . ', ' . Ward::getNamebyId($address['ward_id']) . ', ' . District::getNamebyId($address['district_id']) . ', ' . Province::getNamebyId($address['province_id']);
        $order->province_id = $address['province_id'];
        $order->district_id = $address['district_id'];
        $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
        $order->payment_method = 'QR';
        $order->shipfee = 0;
        //khoi tao order
        if (!$order->getErrors()) {
            if ($order->save()) {
                // $price = $price;
                //khoi tao orderShop
                $orderShop->order_id = $order->id;
                $orderShop->user_id = $user_id;
                $orderShop->shop_id = $shop->id;
                $orderShop->getShopAddressSlected($id_adress_shop);
                $orderShop->order_total = $price;
                //van chuyen
                $orderShop->transport_type = $transport_type;
                $orderShop->shipfee = 0;
                $orderShop->transport_id = $transport_id;
                //van chuyen
                if ($orderShop->save()) {
                    $model_item = new \common\models\order\OrderItem();
                    $model_item->order_id = $order->id;
                    $model_item->order_shop_id = $orderShop->id;
                    $model_item->shop_id = $product['shop_id'];
                    $model_item->product_id = $product['id'];
                    $model_item->code = $product['code'];
                    $model_item->price = $price /$quantity;
                    $model_item->quantity = $quantity;
                    $model_item->status =  1;
                    if ($model_item->save()) {
                        $notify = [];
                        $notify['title'] = Yii::t('app', 'have_new_order');
                        $notify['description'] = Yii::t('app', 'order_ms_0') . $product['name'] . Yii::t('app', 'order_ms_1') . $quantity;
                        $notify['link'] = \common\components\ClaUrl::to(['/management/order/index']);
                        $notify['type'] = Notifications::ORDER;
                        $notify['recipient_id'] = $shop->id;
                        Notifications::pushMessage($notify);
                    }
                    if($orderShop->transport_type > 0) {
                        $free_rp = \frontend\components\Transport::getInfoTransport($orderShop, $data_pr);
                        if ($free_rp && isset($free_rp['order']) && $free_rp['order']) {
                            $orderShop->shipfee = $free_rp['fee'];
                            $orderShop->transport_id = $free_rp['order'];
                            if($orderShop->shipfee > 0) {
                                $order->shipfee = $orderShop->shipfee;
                                $order->order_total = $order->order_total + $order->shipfee;
                                $order->save(false);
                            }
                        } else {
                            $orderShop->transport_type = 0;
                        }
                        $orderShop->save();
                    }
                    $mail = new \common\components\mail\Mail();
                    if($shop['email']) {
                        $mail->sendMail([
                            'email' => $shop['email'],
                            'title' => 'Đơn hàng mới tại OCOP',
                            'content' => $mail->render('email_shop', [
                                'orderShop' => $orderShop
                            ])
                        ]);
                    }
                    if($address['email']) {
                        $mail->sendMail([
                            'email' => $address['email'],
                            'title' => 'Tạo đơn hàng thành công tại OCOP',
                            'content' => $mail->render('email_user', [
                                'orderShop' => $orderShop
                            ])
                        ]);
                    }
                    $siteinfo = \common\components\ClaLid::getSiteinfo();
                    $email_manager = $siteinfo->email;
                    $items[] = [
                            'name' => $product->name,
                            'quantity' => $quantity,
                            'price' => $price /$quantity
                        ];
                    if($email_manager) {
                        $mail->sendMail([
                            'email' => $email_manager,
                            'title' => 'Tạo đơn hàng thành công tại OCOP',
                            'content' => $mail->render('email_manager', [
                                'orderShop' => $orderShop,
                                'address' => $address,
                                'shop' => $shop,
                                'items' => $items
                            ])
                        ]);
                    }
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $dataos = [
                        'order_id' => $orderShop->id,
                        'type' => $orderShop->transport_type,
                        'time' => time(),
                        'status' => 1,
                        'content' => Yii::t('app', 'created_order'),
                    ];
                    $kt = \common\models\order\OrderShopHistory::saveData($dataos);
                    
                    return $order;
                }
            }
        }
        return false;
    }

    public static function UpdateOrder($options) {
        // echo "324324"; die();
        $order = \common\models\order\Order::findOne($options['data']['order_id']);
        if($order) {
            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
            // $order->payment_method = 'QR';
            if($order->save()) {
                $user_id = $options['user_id'];
                $address = \common\models\user\UserAddress::find()->where(['user_id' => $user_id, 'isdefault' => \common\components\ClaLid::STATUS_ACTIVED])->one();
                if(isset($address['email']) && $address['email']) {
                    $mail = new \common\components\mail\Mail();
                    $mail->sendMail([
                        'email' => $address['email'],
                        'title' => 'Bạn đã thanh toán bằng mã QR thành công tại OCOP',
                        'content' => 'Bạn đã thanh toán bằng mã QR thành công cho đơn hàng OR'.$order->id.' trên <a href="ocopmart.org">ocopmart.org</a><br/>Số tiền đã thành toán là :'. number_format($options['price'], 0, ',', '.').' Đ'
                    ]);
                }
                return $order;
            }
        }
        return false;
    }

    public static function getImgQR($options) {
        $type = $options['type'];
        $price = $options['price'];
        switch ($type) {
            case 'product':
                $product_id = $options['product_id'];
                $tranport_type = $options['tranport_type'];
                $quantity = $options['quantity'];
                $shipfee = $options['shipfee'];
                $province = $options['province'];
                $district = $options['district'];
                $address_id_from = $options['address_id_from'];
                $qrcode = [
                    'type' => $type,
                    'price' => $price,
                    // 'user_id' => 4,
                    'data' => [
                        'id' => $product_id,
                        'tranport_type' => $tranport_type,
                        'quantity' => $quantity,
                        'shipfee' => $shipfee,
                        'province' => $province,
                        'district' => $district,
                        'address_id_from' => $address_id_from
                    ]
                ];
                break;
            case 'order':
                $order_id = $options['order_id'];
                $qrcode = [
                    'type' => $type,
                    'price' => $price,
                    // 'user_id' => 4,
                    'data' => [
                        'order_id' => $order_id,
                    ]
                ];
                break;
        }
        $src = ClaQrCode::GenQrCode($qrcode);
        return $src;
    }
}