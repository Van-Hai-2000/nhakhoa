<?php

namespace common\models\kpi;

use backend\models\UserAdmin;
use Yii;

/**
 * This is the model class for table "kpi_user".
 *
 * @property string $id
 * @property string $user_id
 * @property string $kpi_id
 * @property integer $dinh_muc
 * @property integer $thuc_dat
 * @property integer $trong_so
 * @property integer $tru_kpi
 * @property string $ghi_chu
 * @property string $nguoi_danh_gia
 * @property integer $created_at
 * @property integer $updated_at
 */
class KpiUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kpi_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'kpi_id', 'nguoi_danh_gia', 'created_at', 'updated_at'], 'integer'],
            [['dinh_muc', 'thuc_dat'], 'number'],
            [['ghi_chu'], 'string', 'max' => 255],
            [['trong_so', 'tru_kpi'], 'integer', 'max' => 100],
            [['trong_so', 'tru_kpi'], 'integer', 'min' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User Admin Id',
            'kpi_id' => 'Kpi Id',
            'dinh_muc' => 'Định mức khoán',
            'thuc_dat' => 'Thực tế đạt được',
            'trong_so' => 'Trọng số (0 - 100%)',
            'tru_kpi' => 'Trừ KPI (0 - 100%)',
            'ghi_chu' => 'Ghi chú',
            'nguoi_danh_gia' => 'Người đánh giá',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày sửa',
        ];
    }
    
    public function getKpi() {
        return $this->hasOne(Kpi::className(), ['id' => 'kpi_id']);
    }

    public function getUserAdmin() {
        return $this->hasOne(UserAdmin::className(), ['id' => 'user_id']);
    }
}
