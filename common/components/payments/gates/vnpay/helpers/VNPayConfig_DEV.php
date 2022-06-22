<?php

namespace common\components\payments\gates\vnpay\helpers;

use Yii;
use yii\base\Component;

class VNPayConfig_DEV extends Component {

    public $vnp_TmnCode = "TOANCAU2"; //Mã website tại VNPAY 
    public $vnp_HashSecret = "VCEBMNITJKGAHEMKUIOCQFRNXINMPLJS"; //Chuỗi bí mật
    public $RECEIVER = 'vietnam.gpc@gmail.com'; // Email tài khoản ngân lượng
    public $vnp_Url = "https://pay.vnpay.vn/vpcpay.html";
    public $vnp_Url_Test = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    public $mode = 'test'; // real, test Chế độ test hay chạy thật
}
