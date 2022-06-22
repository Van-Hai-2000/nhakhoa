<?php

namespace common\components\payments\gates\vnpay\helpers;

use Yii;
use yii\base\Component;

class VNPayConfig extends Component {

    public $vnp_TmnCode = "TOANCAU1"; //Mã website tại VNPAY 
    public $vnp_HashSecret = "RHHFYYXVWJSCPSTSBYTQLNFRFJYPZNME"; //Chuỗi bí mật
    public $RECEIVER = 'lenamkhanh@gcaeco.vn'; // Email tài khoản ngân lượng
    public $vnp_Url = "https://pay.vnpay.vn/vpcpay.html";
    public $vnp_Url_Test = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    public $mode = 'real'; // real, test Chế độ test hay chạy thật
}