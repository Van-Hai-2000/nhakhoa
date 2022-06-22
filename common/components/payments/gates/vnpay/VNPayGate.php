<?php

/**
 *
 * @author minhbn
 */

namespace common\components\payments\gates\vnpay;

use Yii;
use yii\web\Controller;
use common\components\payments\gates\PaymentGate;
use common\components\payments\gates\vnpay\helpers\VNPayConfig;
use common\components\payments\gates\vnpay\helpers\VNPayConfig_DEV;
use common\components\payments\gates\vnpay\helpers\VNPayHelper;
use common\components\payments\gates\vnpay\models\UserCharge;

//
class VNPayGate extends PaymentGate {

    public $configs = null;

    function __construct($options = []) {
        $this->loadOptions($options);
    }

    protected function loadOptions($options = array()) {
        $this->configs = new VNPayConfig();
        if (defined('YII_ENV') && YII_ENV==='dev') {
            $this->configs = new VNPayConfig_DEV();
        }
    }

    /**
     * Xư ly giao dich
     * @param type $options
     */
    protected function process($options = array()) {
        if (!$this->order) {
            return false;
        }
        $this->payOnline();
        return true;
    }

    /**
     * Thanh toan ví tiêu dùng của member
     */
    protected function payOnline() {
        if ($this->configs->mode === 'test') {
            $vnPayCheckout = new VNPayHelper($this->configs->vnp_TmnCode, $this->configs->vnp_HashSecret, $this->configs->RECEIVER, $this->configs->vnp_Url_Test);
        } else {
            $vnPayCheckout = new VNPayHelper($this->configs->vnp_TmnCode, $this->configs->vnp_HashSecret, $this->configs->RECEIVER, $this->configs->vnp_Url);
        }
        //
        $total_amount = floatval($this->order["order_total"]) * 100;
        //
        $array_items = array();
        //
        $payment_method = $this->order["payment_method"];
        $bank_code = isset($this->order["payment_method_child"]) ? $this->order["payment_method_child"] : '';
        $order_code = $this->order["id"]; // mã charge
        //
        $payment_type = 1; // thanh toán ngay ko tạm giữ
        $discount_amount = 0;
        $tax_amount = 0;
        $fee_shipping = 0;
        $return_url = Yii::$app->urlManager->createAbsoluteUrl(['/product/shoppingcart/paysuccess', 'id' => $order_code, 'key' => $this->order["key"]]);
        $cancel_url = Yii::$app->urlManager->createAbsoluteUrl(['/product/shoppingcart/paycancel', 'id' => $order_code, 'key' => $this->order["key"]]);
        //
        $buyer_fullname = $this->order->name; // Tên người nạp tiền
        $buyer_email = $this->order->email; // Email người nạp tiền
        $buyer_mobile = $this->order->phone; // Điện thoại người nạp tiền
        $buyer_address = 'Hà Nội';
        $order_description = json_encode(array('order_id' => $order_code, 'total' => $total_amount, 'bank_code' => $bank_code,));
        //$order_description = "nội dung";
        //
        if ($payment_method != '' && $bank_code != "" && $order_description != "" && $total_amount > 0) {
            $mem_result = $vnPayCheckout->BankCheckout($order_code, $total_amount, $order_description, $return_url, $cancel_url, $bank_code);
        }
        if (isset($mem_result) && $mem_result->error_code == '00') {
            $url_checkout = (string) $mem_result->checkout_url;
            Yii::$app->response->redirect($url_checkout);
            Yii::$app->end();
        } else {
            echo isset($mem_result) ? $mem_result->error_message : '';
            Yii::$app->response->redirect(Yii::$app->homeUrl);
        }
    }

    /**
     * ham callback khi thanh toan thanh cong
     * @param type $options
     */
    function paySuccess($options = array()) {
        if ($this->configs->mode === 'test') {
            $vnPayCheckout = new VNPayHelper($this->configs->vnp_TmnCode, $this->configs->vnp_HashSecret, $this->configs->RECEIVER, $this->configs->vnp_Url_Test);
        } else {
            $vnPayCheckout = new VNPayHelper($this->configs->vnp_TmnCode, $this->configs->vnp_HashSecret, $this->configs->RECEIVER, $this->configs->vnp_Url);
        }
        //
        return $vnPayCheckout->paySuccess($options);
        
        //
        if ($mem_result) {
            if ((string) $mem_result->order_code !== (string) $this->order->id) {
                return false;
            }
            $nl_errorcode = (int) $mem_result->error_code;
            $nl_transaction_status = (int) $mem_result->status;
            if ($nl_errorcode === 0) {
                if ($nl_transaction_status === 1) {
                    //log
                    $json = json_encode($mem_result);
                    $arr = json_decode($json, true);
                    $log = \common\components\payments\gates\vnpay\models\LogVnPay::findOne($this->order->id);
                    if ($log === NULL) {
                        $log = new \common\components\payments\gates\member\models\LogPaymentMember();
                        $log->transaction_id = $arr['transaction_id'];
                        $log->token = $arr['token'];
                        $log->receiver_email = $arr['receiver_email'];
                        $log->order_code = $arr['order_code'];
                        $log->total_amount = $arr['total_amount'];
                        $log->payment_method = $arr['payment_method'];
                        $log->bank_code = $arr['bank_code'];
                        $log->payment_type = $arr['payment_type'];
                        $log->tax_amount = $arr['tax_amount'];
                        $log->discount_amount = $arr['discount_amount'];
                        $log->fee_shiping = $arr['fee_shipping'];
                        $log->return_url = $arr['return_url'];
                        $log->cancel_url = $arr['cancel_url'];
                        $log->buyer_fullname = $arr['buyer_fullname'];
                        $log->buyer_email = $arr['buyer_email'];
                        $log->buyer_mobile = $arr['buyer_mobile'];
                        $log->buyer_address = $arr['buyer_address'];
                        if ($log->save()) {
                            
                        }
                    }
                    //trạng thái thanh toán thành công
                    $this->order->payment_status = UserCharge::PAYMENT_STATUS_SUCCESS;
                    // Cập nhật tiền cho user nếu trạng thái đơn hàng là chưa cập nhật tiền
                    $this->order->save();
                    return true;
                }
            } else {
                $transStatus = 'Chữ ký không hợp lệ';
                echo $transStatus;
                //echo $vnPayCheckout->GetErrorMessage($nl_errorcode);
                die();
            }
        }
        return false;
    }

    /**
     * khi dung giao dich khong thanh toan nua
     * @param type $options
     */
    function cancel($options = array()) {
        
    }

}
