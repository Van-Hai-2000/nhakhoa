<?php

namespace common\components\payments\gates\vnpay\helpers;

use yii\base\Component;

//
class VNPayHelper extends Component {

    public $url_api = 'https://pay.vnpay.vn/vpcpay.html';
    public $vnp_TmnCode = '';
    public $vnp_HashSecret = '';
    public $receiver_email = '';
    public $cur_code = 'VND';
    public $version = '';
    public $lang_code = 'vn';
    public $vnp_OrderType = 'billpayment';

    function __construct($vnp_TmnCode, $vnp_HashSecret, $receiver_email, $url_api) {
        $this->version = '2.0.0';
        $this->url_api = $url_api;
        $this->vnp_TmnCode = $vnp_TmnCode;
        $this->vnp_HashSecret = $vnp_HashSecret;
        $this->receiver_email = $receiver_email;
    }

    function GetTransactionDetail($token) {
        ###################### BEGIN #####################
        $params = array(
            'vnp_TmnCode' => $this->vnp_TmnCode,
            'vnp_HashSecret' => MD5($this->vnp_HashSecret),
            'version' => $this->version,
            'function' => 'GetTransactionDetail',
            'token' => $token
        );

        $post_field = '';
        foreach ($params as $key => $value) {
            if ($post_field != '')
                $post_field .= '&';
            $post_field .= $key . "=" . $value;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url_api_get);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        if ($result != '' && $status == 200) {
            $nl_result = json_decode($result);
            return $nl_result;
        }
        return false;
        ###################### END #####################
    }

    /*

      Hàm lấy link thanh toán bằng thẻ visa
      ===============================
      Tham số truyền vào bắt buộc phải có
      order_code
      total_amount
      payment_method

      buyer_fullname
      buyer_email
      buyer_mobile
      ===============================
      $array_items mảng danh sách các item name theo quy tắc
      item_name1
      item_quantity1
      item_amount1
      item_url1
      .....
      payment_type Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
     */

    function BankCheckout($order_code, $total_amount, $order_description, $return_url, $cancel_url, $bank_code) {
        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $total_amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => $this->cur_code,
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
            "vnp_Locale" => $this->lang_code,
            "vnp_OrderInfo" => $order_description,
            "vnp_OrderType" => $this->vnp_OrderType,
            "vnp_ReturnUrl" => $return_url,
            "vnp_TxnRef" => $order_code,
        );

        if (isset($bank_code) && $bank_code != "") {
            $inputData['vnp_BankCode'] = $bank_code;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->url_api . "?" . $query;
        $vnp_HashSecret = $this->vnp_HashSecret;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=MD5&vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = (object) array(
                    'error_code' => '00',
                    'message' => 'success',
                    'checkout_url' => $vnp_Url,
        );
        return $returnData;
    }

    function paySuccess($options = []) {
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . $key . "=" . $value;
            } else {
                $hashData = $hashData . $key . "=" . $value;
                $i = 1;
            }
        }

        $secureHash = md5($this->vnp_HashSecret . $hashData);
        $newDateString = null;
        if ($_GET['vnp_PayDate']) {
            $myDateTime = date('YmdHis', $_GET['vnp_PayDate']);
            $newDateString = $myDateTime;
        }
        $status = $_GET['vnp_ResponseCode'];
        if ($secureHash == $vnp_SecureHash) {
            $order_id = $_GET['vnp_TxnRef'];
            $log = \common\components\payments\gates\vnpay\models\LogVnPay::getModel($order_id);
            if(!$log->status) {
                $log->order_id = $order_id;
                $log->amount = $_GET['vnp_Amount'];
                $log->order_code = $_GET['vnp_TransactionNo'];
                $log->created_time = $newDateString;
                $log->site_id = 1;
                $log->bank_code = $_GET['vnp_BankCode'];
                $log->sign = $hashData;
                $log->status = 1;
                $log->save();
            }
            if($log->correct) {
                $order = \common\models\order\Order::findOne($log->order_id);
                if($order) {
                    $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
                    if($order->save()) {
                        return $order;
                    }
                }
            }
            $transStatus = 'Giao dịch thành công';
            return true;
        } else {
            $transStatus = 'Chữ ký không hợp lệ';
            echo $transStatus;
            die();
        }
        return false;
    }

    function CheckoutCall($post_field) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url_api);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        //
        if ($result != '' && $status == 200) {
            //$xml_result = str_replace('&', '&amp;', (string) $result);
            //$nl_result = simplexml_load_string($xml_result);
            $nl_result = json_decode($result);
            if (!$nl_result) {
                $nl_result = (object) array();
            }
            $nl_result->error_message = $this->GetErrorMessage($nl_result->error_code);
        } else {
            $nl_result->error_message = $error;
        }
        return $nl_result;
    }

    function GetErrorMessage($error_code) {
        $arrCode = array(
            '00' => 'Thành công',
            '99' => 'Lỗi chưa xác minh',
            '06' => 'Mã merchant không tồn tại hoặc bị khóa',
            '02' => 'Địa chỉ IP truy cập bị từ chối',
            '03' => 'Mã checksum không chính xác, truy cập bị từ chối',
            '04' => 'Tên hàm API do merchant gọi tới không hợp lệ (không tồn tại)',
            '05' => 'Sai version của API',
            '07' => 'Sai mật khẩu của merchant',
            '08' => 'Địa chỉ email tài khoản nhận tiền không tồn tại',
            '09' => 'Tài khoản nhận tiền đang bị phong tỏa giao dịch',
            '10' => 'Mã đơn hàng không hợp lệ',
            '11' => 'Số tiền giao dịch lớn hơn hoặc nhỏ hơn quy định',
            '12' => 'Loại tiền tệ không hợp lệ',
            '29' => 'Token không tồn tại',
            '80' => 'Không thêm được đơn hàng',
            '81' => 'Đơn hàng chưa được thanh toán',
            '110' => 'Địa chỉ email tài khoản nhận tiền không phải email chính',
            '111' => 'Tài khoản nhận tiền đang bị khóa',
            '113' => 'Tài khoản nhận tiền chưa cấu hình là người bán nội dung số',
            '114' => 'Giao dịch đang thực hiện, chưa kết thúc',
            '115' => 'Giao dịch bị hủy',
            '118' => 'tax_amount không hợp lệ',
            '119' => 'discount_amount không hợp lệ',
            '120' => 'fee_shipping không hợp lệ',
            '121' => 'return_url không hợp lệ',
            '122' => 'cancel_url không hợp lệ',
            '123' => 'items không hợp lệ',
            '124' => 'transaction_info không hợp lệ',
            '125' => 'quantity không hợp lệ',
            '126' => 'order_description không hợp lệ',
            '127' => 'affiliate_code không hợp lệ',
            '128' => 'time_limit không hợp lệ',
            '129' => 'buyer_fullname không hợp lệ',
            '130' => 'buyer_email không hợp lệ',
            '131' => 'buyer_mobile không hợp lệ',
            '132' => 'buyer_address không hợp lệ',
            '133' => 'total_item không hợp lệ',
            '134' => 'payment_method, bank_code không hợp lệ',
            '135' => 'Lỗi kết nối tới hệ thống ngân hàng',
            '140' => 'Đơn hàng không hỗ trợ thanh toán trả góp',);

        return $arrCode[(string) $error_code];
    }

}
