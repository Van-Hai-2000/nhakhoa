<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "{{%shop_store}}".
 *
 * @property string $id
 * @property string $name
 * @property boolean $status
 * @property string $address
 * @property string $province_id
 * @property string $province_name
 * @property string $district_id
 * @property string $district_name
 * @property string $ward_id
 * @property string $ward_name
 * @property string $hotline
 * @property string $phone
 * @property string $email
 * @property string $hours
 * @property string $avatar_path
 * @property string $avatar_name
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $site_id
 * @property integer $shop_id
 * @property string $latlng
 * @property integer $group
 * @property integer $order
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 */
class ShopStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'province_id', 'province_name', 'district_id', 'district_name', 'ward_id', 'ward_name', 'hotline', 'phone', 'email', 'hours', 'avatar_path', 'avatar_name', 'created_time', 'modified_time', 'site_id', 'shop_id', 'group', 'meta_title', 'meta_keywords', 'meta_description'], 'required'],
            [['status'], 'boolean'],
            [['created_time', 'modified_time', 'site_id', 'shop_id', 'group', 'order'], 'integer'],
            [['name', 'address', 'email', 'avatar_path', 'avatar_name', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            [['province_id', 'district_id', 'ward_id'], 'string', 'max' => 5],
            [['province_name', 'district_name', 'ward_name', 'hotline', 'phone'], 'string', 'max' => 50],
            [['hours'], 'string', 'max' => 500],
            [['latlng'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'address' => 'Address',
            'province_id' => 'Province ID',
            'province_name' => 'Province Name',
            'district_id' => 'District ID',
            'district_name' => 'District Name',
            'ward_id' => 'Ward ID',
            'ward_name' => 'Ward Name',
            'hotline' => 'Hotline',
            'phone' => 'Phone',
            'email' => 'Email',
            'hours' => 'Hours',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'site_id' => 'Site ID',
            'shop_id' => 'Shop ID',
            'latlng' => 'Latlng',
            'group' => 'Group',
            'order' => 'Order',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
        ];
    }
}
