<?php
namespace common\components\payments\gates\nganluong\helpers;
use Yii;
use yii\base\Component;

class NganluongBankHelper extends Component {

    public static function optionBankAtmOnline() {
        $option = array(
            'BIDV' => 'Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam',
            'VCB' => 'Ngân hàng TMCP Ngoại Thương Việt Nam',
            'DAB' => 'Ngân hàng Đông Á',
            'TCB' => 'Ngân hàng Kỹ Thương',
            'MB' => 'Ngân hàng Quân Đội',
            'VIB' => 'Ngân hàng Quốc tế',
            'ICB' => 'Ngân hàng Công Thương Việt Nam',
            'EXB' => 'Ngân hàng Xuất Nhập Khẩu',
            'ACB' => 'Ngân hàng Á Châu',
            'HDB' => 'Ngân hàng Phát triển Nhà TPHCM',
            'MSB' => 'Ngân hàng Hàng Hải',
            'NVB' => 'Ngân hàng Nam Việt',
            'VAB' => 'Ngân hàng Việt Á',
            'VPB' => 'Ngân Hàng Việt Nam Thịnh Vượng',
            'SCB' => 'Ngân hàng Sài Gòn Thương tín',
            'PGB' => 'Ngân hàng Xăng dầu Petrolimex',
            'GPB' => 'Ngân hàng TMCP Dầu khí Toàn Cầu',
            'AGB' => 'Ngân hàng Nông nghiệp &amp; Phát triển nông thôn',
            'SGB' => 'Ngân hàng Sài Gòn Công Thương',
            'BAB' => 'Ngân hàng Bắc Á',
            'TPB' => 'Tền phong bank',
            'NAB' => 'Ngân hàng Nam Á',
            'SHB' => 'Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)',
            'OJB' => 'Ngân hàng TMCP Đại Dương (OceanBank)'
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
