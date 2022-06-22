<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/11/2021
 * Time: 10:03 AM
 */

namespace common\components;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use common\models\commission\Commission;
use common\models\District;
use common\models\LoaiMau;
use common\models\medical_record\Factory;
use common\models\medical_record\MedicalRecordItemCommission;
use common\models\medical_record\MedicalRecordLog;
use common\models\medicine\Medicine;
use common\models\product\ProductCategory;
use common\models\Province;
use common\models\thuchi\ThuChi;
use common\models\thuchi\ThuChiCategory;
use common\models\user\MedicalRecord;
use common\models\user\PaymentHistory;
use common\models\user\User;
use common\models\Ward;
use yii\db\Exception;

class ClaNhakhoa
{
    public $data_return;
    public $is_return = 0;
    const TYPE_MONEY_1 = 1; //Theo %
    const TYPE_MONEY_2 = 2; // Theo tiền mặt
    public static function getTotal($provider, $fieldName)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        return $total;
    }

    public static function getTotalByQuantity($provider, $fieldName)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName] * $item['quantity'];
        }

        return $total;
    }

    //Tổng tiền công nợ
    public static function getTotalNo($provider, $fieldName1, $fieldName2)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += ($item[$fieldName2] - $item[$fieldName1]);
        }

        return $total;
    }

    static function checkEditCommission($time_check, $time)
    {
        $total_day_check = date('t', $time_check);

        $day_now = date('d', time());
        $month_now = date('m', time());
        $year_now = date('Y', time());

        $month_check = date('m', $time_check);
        $year_check = date('Y', $time_check);
        if ($month_check == $month_now && $year_check == $year_now) {
            return true;
        }

        if (($total_day_check + $time) >= ($total_day_check + $day_now)) {
            return true;
        }
        return false;
    }

    static function getContentLog($text)
    {
        $html = '<div>';
        $content = json_decode($text, true);
        if ($content) {
            foreach ($content as $value) {
                foreach ($value as $key => $val) {
                    $html .= $key . ': ';
                    $html .= $val;
                    $html .= '<br>';
                }
            }
        }
        $html .= '</div>';
        return $html;
    }

    static function deleteKeyArr($arr = [], $keys = [])
    {
        foreach ($keys as $key) {
            $key_arr = array_keys($arr);
            if (in_array($key, $key_arr)) {
                unset($arr[$key]);
            }
        }
        return $arr;
    }

    //lấy giá trị thay đổi user
    static function getValueLogUser($arr = [],$action = '')
    {
        $response = [];
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'status':
                    $response[$key] = $value == 1 ? 'Kích hoạt' : 'Khóa';
                    break;
                case 'sex':
                    $sex = \common\models\user\User::getSex();
                    $response[$key] = isset($sex[$value]) && $sex[$value] ? $sex[$value] : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'birthday':
                    $response[$key] = date('d-m-Y', $value);
                    break;
                case 'introduce':
                    $intro = \backend\models\UserAdmin::getIntroduce();
                    $response[$key] = isset($intro[$value]) && $intro[$value] ? $intro[$value] : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'introduce_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'type_introduce':
                    $type_in = \common\models\user\User::getTypeIntroduce();
                    $response[$key] = isset($type_in[$value]) && $type_in[$value] ? $type_in[$value] : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'admin_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'province_id':
                    $province = Province::findOne($value);
                    $response[$key] = $province ? $province->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'district_id':
                    $district = District::findOne($value);
                    $response[$key] = $district ? $district->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'email':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'src':
                    $response[$key] = $value;
                    if ($value == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'address':
                    $response[$key] = $value;
                    if ($value == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'ward_id':
                    $ward = Ward::findOne($value);
                    $response[$key] = $ward ? $ward->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                default:
                    $response[$key] = $value;
            }
        }
        return $response;
    }

    //lấy giá trị thay đổi lịch hẹn
    static function getValueLogAppointment($arr = [],$action = '')
    {
        $response = [];
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'doctor_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'time':
                    $response[$key] = date('d-m-Y H:i:s', $value);
                    break;
                case 'product_category_id':
                    $product_cat = ProductCategory::findOne($value);
                    $response[$key] = $product_cat ? $product_cat->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'branch_id':
                    $branch = Branch::findOne($value);
                    $response[$key] = $branch ? $branch->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'status':
                    $response[$key] = $value == 1 ? 'Đã đến' : 'Chưa đến';
                    break;
                case 'admin_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'user_id':
                    $user = User::findOne($value);
                    $response[$key] = $user ? $user->username : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'address':
                    $response[$key] = $value;
                    if ($value == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                default:
                    $response[$key] = $value;
            }
        }
        return $response;
    }

    //lấy giá trị thay đổi khi thanh toán
    static function getValueLogPayment($arr = [],$action = '')
    {
        $response = [];
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'doctor_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'created_at':
                    $response[$key] = date('d-m-Y H:i:s', $value);
                    break;
                case 'money':
                    $response[$key] = number_format($value);
                    break;
                case 'branch_id':
                    $branch = Branch::findOne($value);
                    $response[$key] = $branch ? $branch->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'admin_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'type_payment':
                    $type_payment = PaymentHistory::getTypePayment();
                    $response[$key] = isset($type_payment[$value]) && $type_payment[$value] ? $type_payment[$value] : 'Tiền mặt';
                    break;
                case 'type_sale':
                    $response[$key] = $value == 2 ? 'Theo %' : 'Tiền mặt';
                    break;
                case 'pay_sale_description':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'note':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                default:
                    $response[$key] = $value;
            }
        }
        return $response;
    }

    //lấy giá trị thay đổi khi đặt xuowngr
    static function getValueLogFac($arr = [],$action = '')
    {
        $response = [];
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'user_id':
                    $user = User::findOne($value);
                    $response[$key] = $user ? $user->username : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'factory_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'created_at':
                    $response[$key] = $value ? date('d-m-Y H:i:s', $value) : 'Đang cập nhật';
                    break;
                case 'money':
                    $response[$key] = $value ? number_format($value) : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'branch_id':
                    $branch = Branch::findOne($value);
                    $response[$key] = $branch ? $branch->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'admin_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'device_id':
                    $device = LoaiMau::findOne($value);
                    $response[$key] = $device ? $device->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'time_return':
                    $response[$key] = $value ? date('d-m-Y H:i:s', $value) : 'Đang cập nhật';
                    break;
                case 'status':
                    $stt = Factory::getStatus();
                    $response[$key] = isset($stt[$value]) && $stt[$value] ? $stt[$value] : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'status_delete':
                    $response[$key] = $value == 1 ? 'Đã xóa' : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'insurance_code':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'insurance_code_private':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'status_delete':
                    if ($action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                default:
                    $response[$key] = $value;
            }
        }
        return $response;
    }

    //lấy giá trị thay đổi thu chi
    static function getValueLogThuchi($arr = [], $action = '')
    {
        $response = [];
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'user_id':
                    $user = User::findOne($value);
                    $response[$key] = $user ? $user->username : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'type':
                    $type = ThuChi::getType();
                    $response[$key] = isset($type[$value]) && $type[$value] ? $type[$value] : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'type_id':
                    $type = ThuChi::getTypeId();
                    $response[$key] = isset($type[$value]) && $type[$value] ? $type[$value] : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'type_payment':
                    $type = ThuChi::getTypePayment();
                    $response[$key] = isset($type[$value]) && $type[$value] ? $type[$value] : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'created_at':
                    $response[$key] = $value ? date('d-m-Y H:i:s', $value) : 'Đang cập nhật';
                    break;
                case 'time':
                    $response[$key] = $value ? date('d-m-Y H:i:s', $value) : 'Đang cập nhật';
                    break;
                case 'money':
                    $response[$key] = $value ? number_format($value) : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'branch_id':
                    $branch = Branch::findOne($value);
                    $response[$key] = $branch ? $branch->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'admin_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'ncc_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'nguoi_chi':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'category_id':
                    $cat = ThuChiCategory::findOne($value);
                    $response[$key] = $cat ? $cat->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'name':
                    $response[$key] = $value;
                    if ($value == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'note':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'object_type':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'object_id':
                    $response[$key] = $value;
                    if ($value == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'medical_record_id':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'payment_id':
                    $response[$key] = $value;
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'status_delete':
                    $response[$key] = $value == 1 ? 'Đã xóa' : '';
                    if (!$value && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                default:
                    $response[$key] = $value;
            }
        }
        return $response;
    }

    //lấy giá trị thay đổi thuốc
    static function getValueLogMedicine($arr = [],$action = '')
    {
        $response = [];
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'user_id':
                    $user = User::findOne($value);
                    $response[$key] = $user ? $user->username : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'type':
                    $type = ThuChi::getType();
                    $response[$key] = isset($type[$value]) && $type[$value] ? $type[$value] : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'medicine_id':
                    $medicine = Medicine::findOne($value);
                    $response[$key] = $medicine ? $medicine->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'doctor_id':
                    $user_admin = UserAdmin::findOne($value);
                    $response[$key] = $user_admin ? $user_admin->fullname : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'money':
                    $response[$key] = $value ? number_format($value) : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'status_delete':
                    $response[$key] = $value == 1 ? 'Đã xóa' : '';
                    break;
                case 'branh_id':
                    $branch = Branch::findOne($value);
                    $response[$key] = $branch ? $branch->name : '';
                    if ($response[$key] == '' && $action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                case 'created_at':
                    $response[$key] = $value ? date('d-m-Y H:i:s', $value) : 'Đang cập nhật';
                    break;
                case 'status_delete':
                    if ($action == 'create') {
                        unset($response[$key]);
                    }
                    break;
                default:
                    $response[$key] = $value;
            }
        }
        return $response;
    }

    static function getValueLogCom($arr = [])
    {
        $response = [];
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'price_payment':
                    $response[$key] = $value ? number_format($value) : 0;
                    break;
                case 'user_id':
                    if ($value) {
                        $vl = explode(',', $value);
                        $user_com = UserAdmin::find()->where(['id' => $vl])->asArray()->all();
                        $user_com = array_column($user_com, 'fullname', 'id');
                        $response[$key] = implode(',', $user_com);
                    }
                    break;
                case 'value':
                    $type = [];
                    if ($value) {
                        $vl = explode(',', $value);
                        foreach ($vl as $val) {
                            if ($val > 8) {
                                array_push($type, number_format($val) . 'vnđ');
                            } else {
                                array_push($type, number_format($val) . '%');
                            }
                        }
                        $response[$key] = implode(',', $type);
                    }
                    break;
                case 'type':
                    $type = [];
                    if ($value) {
                        $vl = explode(',', $value);
                        foreach ($vl as $val) {
                            if ($val == MedicalRecordItemCommission::TYPE_1) {
                                array_push($type, 'Hưởng theo %');
                            } else {
                                array_push($type, 'Hưởng theo tiền mặt');
                            }
                        }
                        $response[$key] = implode(',', $type);
                    }
                    break;
                case 'created_at':
                    $response[$key] = $value ? date('d-m-Y H:i:s', $value) : 'Đang cập nhật';
                    break;
                default:
                    $response[$key] = $value;
            }
        }
        return $response;
    }

    static function getValueLogMedical($arr = [],$action = '')
    {
        $response = [];
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'user_id':
                    if ($value) {
                        $user = User::findOne($value);
                        $response[$key] = $user->username;
                    }
                    break;
                case 'branch_id':
                    $branch = Branch::findOne($value);
                    $response[$key] = $branch ? $branch->name : '';
                    break;
                case 'created_at':
                    $response[$key] = $value ? date('d-m-Y H:i:s', $value) : 'Đang cập nhật';
                    break;
                case 'status':
                    $status = MedicalRecord::getStatus();
                    $response[$key] = isset($status[$value]) && $status[$value] ? $status[$value] : '';
                    if ($response[$key] == '') {
                        unset($response[$key]);
                    }
                    break;
                default:
                    $response[$key] = $value;
            }
        }
        return $response;
    }
    static function check_array($p_array){
        if(is_array($p_array) and sizeof($p_array)>0){
            return true;
        }else{
            return false;
        }
    }

    //Lấy tổng dữ liệu 1 field trong 1 mảng nhiều dữ liệu
    static function getSum($arr,$field_name){
        $sum = 0;
        if(self::check_array($arr)){
            foreach ($arr as $value){
                if(isset($value[$field_name]) && $value[$field_name]){
                    $sum += intval($value[$field_name]);
                }
            }
        }
        return $sum;
    }
}

