<?php
/**
 * Created by PhpStorm.
 * User: trung
 * Date: 10/14/2021
 * Time: 2:06 PM
 */


namespace common\components;

class ClaLog
{
    const ACTION_UPDATE = 'Cập nhật';
    const ACTION_CREATE = 'Tạo mới';
    const ACTION_DELETE = 'Xóa';

    static function getTableName()
    {
        return [
            'LoaiMau' => 'Loại mẫu',
            'Appointment' => 'Lịch hẹn',
            'Branch' => 'Chi nhánh',
            'MedicalRecord' => 'Hồ sơ bệnh án',
            'Commission' => 'Hoa hồng',
            'MedicalRecordChild' => 'Danh sách thủ thuật trong HSBA',
            'MedicalRecordItem' => 'Chi tiết lịch sử khám bệnh',
            'MedicalRecordItemMedicine' => 'Đơn thuốc',
            'Factory' => 'Đặt xưởng',
            'ProductCategory' => 'Nhóm thủ thuật',
            'ProductCategoryImage' => 'Ảnh nhóm thủ thuật',
            'Product' => 'Thủ thuật',
            'ProductImage' => 'Ảnh thủ thuật',
            'Medicine' => 'Thuốc - Thiết bị',
            'MedicineImage' => 'Ảnh thuốc - thiết bị',
            'MedicineCategory' => 'Danh mục thuốc - thiết bị',
            'MedicineCategoryImage' => 'Ảnh danh mục thuốc - thiết bị',
            'PaymentHistory' => 'Thanh toán',
            'ThuChi' => 'Thu chi',
            'MedicalRecordItemCommission' => 'Hoa hồng khám bệnh',
        ];
    }

    static function getAction(){
        return [
            self::ACTION_CREATE => self::ACTION_CREATE,
            self::ACTION_UPDATE => self::ACTION_UPDATE,
            self::ACTION_DELETE => self::ACTION_DELETE,
        ];
    }
}