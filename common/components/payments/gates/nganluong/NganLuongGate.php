<?php

/**
 *
 * @author minhbn
 */

namespace common\components\payments\gates\nganluong;

use Yii;
use yii\web\Controller;
use common\components\payments\gates\PaymentGate;
use common\components\payments\gates\nganluong\helpers\NganluongConfig;
use common\components\payments\gates\nganluong\helpers\NganluongConfig_DEV;
use common\components\payments\gates\nganluong\helpers\NganluongHelper;
use common\components\payments\gates\nganluong\models\UserCharge;

//
class NganLuongGate extends PaymentGate {

    public $configs = null;

    function __construct($options = []) {
        $this->loadOptions($options);
    }

    protected function loadOptions($options = array()) {
        $this->configs = new NganluongConfig();
        if (defined('YII_ENV') && YII_ENV==='dev') {
            $this->configs = new NganluongConfig_DEV();
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
     * Thanh toan bang ATM, VISA
     */
    protected function payOnline() {
        $nlcheckout = new NganluongHelper($this->configs->MERCHANT_ID, $this->configs->MERCHANT_PASS, $this->configs->RECEIVER, $this->configs->URL_API);
        //
        $total_amount = $this->order["order_total"];
        //
        $array_items = array();
        //
        $payment_method = $this->order["payment_method"];
        $bank_code = isset($this->order["payment_method_child"]) ? $this->order["payment_method_child"] : '';
        $order_code = $this->order["id"]; // mã charge
        //
        $payment_type = 1; // thanh toán ngay ko tạm giữ
        $discount_amount = 0;
        $order_description = '';
        $tax_amount = 0;
        $fee_shipping = 0;
        $return_url = urlencode(Yii::$app->urlManager->createAbsoluteUrl(['/product/shoppingcart/paysuccess','id' => $order_code, 'key' => $this->order["key"]]));
        $cancel_url = urlencode(Yii::$app->urlManager->createAbsoluteUrl(['/product/shoppingcart/paycancel', 'id' => $order_code, 'key' => $this->order["key"]]));
        //
        $buyer_fullname = $this->order->name; // Tên người nạp tiền
        $buyer_email = $this->order->email; // Email người nạp tiền
        $buyer_mobile = $this->order->phone; // Điện thoại người nạp tiền
        if (!$buyer_mobile && $payment_method == UserCharge::PAYMENT_METHOD_NL) {
            $buyer_mobile = 'true';
        }
        $buyer_address = 'Hà Nội';
        //
        if ($payment_method != '' && $buyer_email != "" && $buyer_mobile != "" && $buyer_fullname != "" && filter_var($buyer_email, FILTER_VALIDATE_EMAIL)) {
            if ($payment_method == UserCharge::PAYMENT_METHOD_VISA) {
                $nl_result = $nlcheckout->VisaCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items, $bank_code);
            } elseif ($payment_method == UserCharge::PAYMENT_METHOD_NL) {
                $nl_result = $nlcheckout->NLCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            } elseif ($payment_method == UserCharge::PAYMENT_METHOD_ATM_ONLINE && $bank_code != '') {
                $nl_result = $nlcheckout->BankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            } elseif ($payment_method == UserCharge::PAYMENT_METHOD_NH_OFFLINE) {
                $nl_result = $nlcheckout->officeBankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            } elseif ($payment_method == UserCharge::PAYMENT_METHOD_ATM_OFFLINE) {
                $nl_result = $nlcheckout->BankOfflineCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            } elseif ($payment_method == UserCharge::PAYMENT_METHOD_IB_ONLINE) {
                $nl_result = $nlcheckout->IBCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            }
        }
        if (isset($nl_result) && $nl_result->error_code == '00') {
            $url_checkout = (string) $nl_result->checkout_url;
            Yii::$app->response->redirect($url_checkout);
            Yii::$app->end();
        } else {
            echo isset($nl_result) ? $nl_result->error_message : '';
            Yii::$app->response->redirect(Yii::$app->homeUrl);
        }
    }

    /**
     * ham callback khi thanh toan thanh cong
     * @param type $options
     */
    function paySuccess($options = array()) {
        $token = Yii::$app->request->get('token', '');
        if ($token == '') {
            return false;
        }
        $nlcheckout = new NganluongHelper($this->configs->MERCHANT_ID, $this->configs->MERCHANT_PASS, $this->configs->RECEIVER, $this->configs->URL_API);
        $nl_result = $nlcheckout->GetTransactionDetail($token);
        if ($nl_result) {
            if ((string)$nl_result->order_code !== (string)$this->order->id) {
                return false;
            }
            $nl_errorcode = (string) $nl_result->error_code;
            $nl_transaction_status = (string) $nl_result->transaction_status;
            if ($nl_errorcode === '00') {
                if ($nl_transaction_status === '00') {
                    //log
                    $json = json_encode($nl_result);
                    $arr = json_decode($json, true);
                    $log = \common\components\payments\gates\nganluong\models\LogPaymentNganluong::findOne($arr['transaction_id']);
                    if ($log === NULL) {
                        $log = new \common\components\payments\gates\nganluong\models\LogPaymentNganluong();
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
                echo $nlcheckout->GetErrorMessage($nl_errorcode);
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
