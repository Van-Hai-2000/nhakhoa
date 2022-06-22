<?php

namespace common\models\affiliate;

use Yii;

/**
 * This is the model class for table "affiliate_config".
 *
 * @property string $id
 * @property string $cookie_expire
 * @property string $commission_order
 * @property string $commission_click
 * @property integer $change_phone
 * @property string $min_price
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class AffiliateConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'affiliate_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cookie_expire'], 'required'],
            [['cookie_expire', 'change_phone', 'min_price', 'status', 'created_at', 'updated_at'], 'integer'],
            [['commission_order', 'commission_click'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cookie_expire' => 'Thời gian lưu Cookie (Ngày)',
            'commission_order' => 'Thưởng đơn hàng (Phần trăm)',
            'commission_click' => 'Thưởng click (VNĐ)',
            'change_phone' => 'Đổi số điện thoại theo người giới thiệu',
            'min_price' => 'Số tiền tối thiểu người giới thiệu có thể rút',
            'status' => 'Bật Affiliate',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function getAffiliateConfig() {
        $config = AffiliateConfig::findOne(ClaLid::ROOT_SITE_ID);
        return $config;
    }
}
