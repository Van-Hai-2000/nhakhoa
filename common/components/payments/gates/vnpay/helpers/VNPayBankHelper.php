<?php

namespace common\components\payments\gates\vnpay\helpers;

use Yii;
use yii\base\Component;

class VNPayBankHelper extends Component {

    public static function optionBankAtmOnline() {
        $option = array(
            'BIDV' => 'Ngân hàng TMCP Đầu tư và Phát triển Việt Nam',
            'VIETCOMBANK' => 'Ngân hàng TMCP Ngoại Thương Việt Nam',
            'DONGABANK' => 'Ngân hàng Đông Á',
            'TECHCOMBANK' => 'Ngân hàng Kỹ Thương',
            'MBBANK' => 'Ngân hàng Quân Đội',
            'VIB' => 'Ngân hàng Quốc tế',
            'VIETINBANK' => 'Ngân hàng Công Thương Việt Nam',
            'EXIMBANK' => 'Ngân hàng Xuất Nhập Khẩu',
            'ACB' => 'Ngân hàng Á Châu',
            'HDB' => 'Ngân hàng Phát triển Nhà TPHCM',
            'MSBANK' => 'Ngân hàng Hàng Hải',
            //'NVB' => 'Ngân hàng Nam Việt',
            //'VAB' => 'Ngân hàng Việt Á',
            'VPBANK' => 'Ngân Hàng Việt Nam Thịnh Vượng',
            'SACOMBANK' => 'Ngân hàng Sài Gòn Thương tín',
            //'PGB' => 'Ngân hàng Xăng dầu Petrolimex',
            //'GPB' => 'Ngân hàng TMCP Dầu khí Toàn Cầu',
            'AGRIBANK' => 'Ngân hàng Nông nghiệp và Phát triển nông thôn',
            //'SGB' => 'Ngân hàng Sài Gòn Công Thương',
            //'BAB' => 'Ngân hàng Bắc Á',
            'TPBANK' => 'Tền phong bank',
            'NAMABANK' => 'Ngân hàng Nam Á',
            //'SHB' => 'Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)',
            'OJB' => 'Ngân hàng TMCP Đại Dương (OceanBank)',
            'NCB' => 'Ngân Hàng Quốc Dân',
            'VNMART' => 'Ví Điện Tử VNMart',
            'OCB' => 'Ngân Hàng Phương Đông',
            'IVB' => 'Ngân hàng TNHH Indovina',
        );
        return $option;
    }

    public static function optionCard() {
        $option = array(
            'VMS' => 'Mobifone',
            'VNP' => 'Vinaphone',
            'VIETTEL' => 'Viettel'
        );
        return $option;
    }

}
