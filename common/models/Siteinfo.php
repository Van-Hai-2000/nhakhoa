<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "siteinfo".
 *
 * @property string $id
 * @property string $title
 * @property string $logo
 * @property string $favicon
 * @property string $footer_logo
 * @property string $email
 * @property string $phone
 * @property string $hotline
 * @property string $address
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property integer $updated_at
 * @property integer $gold_price
 */
class Siteinfo extends \yii\db\ActiveRecord
{

    const ROOT_SITE_ID = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'siteinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'iframe'], 'required'],
            [['created_at', 'updated_at','time_commission'], 'integer'],
            [['title', 'logo', 'footer_logo', 'favicon', 'email', 'meta_keywords', 'meta_description', 'address'], 'string', 'max' => 255],
            [['phone', 'hotline'], 'string', 'max' => 50],
            [['gold_price'], 'number'],
            [['company', 'live_chat', 'number_auth', 'email_rif', 'copyright', 'link_bct', 'video_link'], 'safe'],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Tiêu đề trang',
            'logo' => 'Logo',
            'favicon' => 'Favicon',
            'email' => 'Email',
            'phone' => 'Điện thoại cố định',
            'hotline' => 'Hotline',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'address' => 'Địa chỉ',
            'gold_price' => 'Giá 1 chỉ vàng',
            'footer_logo' => 'Footer logo',
            'iframe' => 'Iframe bản đồ',
            'company' => 'Tên công ty',
            'number_auth' => 'Mã số doanh nghiệp',
            'email_rif' => 'Danh sách nhận thông báo(Phân cách bởi: `,`)',
            'video_link' => 'Link video',
            'time_commission' => 'Ngày chốt hoa hồng hàng tháng'
        ];
    }

    function getMaxFreeTransfer($coin)
    {
        if ($this->transfer_fee_type == 1) {
            return $coin / ($this->transfer_fee / 100 + 1);
        }
        return $coin - $this->transfer_fee;
    }

    function getUnitTransfer()
    {
        if ($this->transfer_fee_type == 1) {
            return '%';
        }
        return 'V';
    }

    function getCoinTransferFee($coin) {
        if ($this->transfer_fee_type == 1) {
            return $coin + ($coin * $this->transfer_fee / 100);
        }
        return $coin +  $this->transfer_fee;
    }
}
