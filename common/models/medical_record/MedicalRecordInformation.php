<?php

namespace common\models\medical_record;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "medical_record_information".
 *
 * @property integer $medical_record_id
 * @property string $qua_trinh_benh_ly
 * @property string $tien_su_ban_than
 * @property string $tien_su_gia_dinh
 * @property string $tam_ly_benh_nhan
 * @property string $toan_than
 * @property string $benh_chuyen_khoa
 * @property string $tom_tat_benh_an
 * @property string $chuan_doan
 * @property string $tinh_trang_ra_vien
 * @property string $huong_dieu_tri
 * @property integer $time_end
 * @property integer $mach
 * @property double $nhiet_do
 * @property string $huyet_ap
 * @property integer $nhip_tho
 * @property double $can_nang
 * @property integer $created_at
 * @property integer $updated_at
 */
class MedicalRecordInformation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medical_record_information';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medical_record_id'], 'required'],
            [['medical_record_id', 'mach', 'nhip_tho', 'created_at', 'updated_at'], 'integer'],
            [['qua_trinh_benh_ly', 'tien_su_ban_than', 'tien_su_gia_dinh', 'tam_ly_benh_nhan', 'tom_tat_benh_an','thu_thuat_da_lam','dien_bien_benh'], 'string'],
            [['nhiet_do', 'can_nang'], 'number'],
            [['toan_than', 'benh_chuyen_khoa', 'chuan_doan', 'tinh_trang_ra_vien', 'huong_dieu_tri','truoc_thu_thuat','sau_thu_thuat','phuong_phap','loai_thu_thuat','phuong_phap_vo_cam'], 'string', 'max' => 500],
            [['bac_si_thu_thuat'], 'string', 'max' => 255],
            [['huyet_ap'], 'string', 'max' => 50],
            [['time_end'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'medical_record_id' => 'Mã hồ sơ bệnh án',
            'qua_trinh_benh_ly' => 'Quá trình bệnh lý',
            'tien_su_ban_than' => 'Tiền sử bệnh bản thân',
            'tien_su_gia_dinh' => 'Tiền sử bệnh gia đình',
            'tam_ly_benh_nhan' => 'Đặc điểm tâm lý bệnh nhân',
            'toan_than' => 'Toàn thân',
            'benh_chuyen_khoa' => 'Bệnh chuyên khoa',
            'tom_tat_benh_an' => 'Tóm tắt bệnh án',
            'chuan_doan' => 'Chuẩn đoán',
            'tinh_trang_ra_vien' => 'Tình trạng ra viện',
            'huong_dieu_tri' => 'Hướng điều trị và các chế độ tiếp theo',
            'time_end' => 'Ngày kết thúc',
            'mach' => 'Mạch (lần/phút)',
            'nhiet_do' => 'Nhiệt độ (oC)',
            'huyet_ap' => 'Huyết áp (mmHg)',
            'nhip_tho' => 'Nhịp thở (lần/phút)',
            'can_nang' => 'Cân nặng (kg)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'truoc_thu_thuat' => 'Trước thủ thuật',
            'sau_thu_thuat' => 'Sau thủ thuật',
            'phuong_phap' => 'Phương pháp làm thủ thuật',
            'loai_thu_thuat' => 'Loại thủ thuật',
            'phuong_phap_vo_cam' => 'Phương pháp vô cảm',
            'bac_si_thu_thuat' => 'Bác sĩ thủ thuật',
            'thu_thuat_da_lam' => 'Thủ thuật đã làm',
            'dien_bien_benh' => 'Diễn biến bệnh',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }
}
