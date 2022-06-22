<?php

namespace common\components\payments\gates\nganluong\helpers;

use Yii;
use yii\base\Component;

class NganluongConfig_DEV extends Component {

    public $NGANLUONG_URL_CARD_POST = "https://www.nganluong.vn/mobile_card.api.post.v2.php";
    public $NGANLUONG_URL_CARD_SOAP = "https://nganluong.vn/mobile_card_api.php?wsdl";
    public $_FUNCTION = "CardCharge";
    public $_VERSION = "2.0";
    public $URL_API = 'https://www.nganluong.vn/checkout.api.nganluong.post.php'; // Đường dẫn gọi API
    public $RECEIVER = 'vietnam.gpc@gmail.com'; // Email tài khoản ngân lượng
    public $MERCHANT_ID = '54823'; // Mã merchant kết nối
    public $MERCHANT_PASS = 'a8399575f4b3d124767e14988a254514'; // Mật khẩu kết nối
//    public $RECEIVER = 'buithanhdung@gmail.com'; // Email tài khoản ngân lượng
//    public $MERCHANT_ID = '52544'; // Mã merchant kết nối
//    public $MERCHANT_PASS = '17237794ecaef582367663fbcb39f09c'; // Mật khẩu kết nối

}
