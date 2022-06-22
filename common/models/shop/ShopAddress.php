<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "{{%shop_address}}".
 *
 * @property string $id
 * @property string $shop_id
 * @property string $name_contact
 * @property string $phone
 * @property integer $province_id
 * @property integer $district_id
 * @property integer $ward_id
 * @property string $address
 * @property integer $isdefaut
 */
class ShopAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'name_contact', 'phone', 'province_id', 'district_id', 'ward_id'], 'required'],
            [['shop_id', 'province_id', 'phone', 'district_id', 'ward_id', 'isdefault'], 'integer'],
            [['name_contact', 'address'], 'string', 'max' => 255],
            [['phone'], 'integer'],
            [['phone'], 'string' ,'min' => 9, 'max' => 13],
            [['province_name', 'district_name', 'ward_name', 'latlng', 'phone_add'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'name_contact' => Yii::t('app', 'name_contact'),
            'phone' => Yii::t('app', 'phone'),
            'province_id' => Yii::t('app', 'province'),
            'district_id' => Yii::t('app', 'district'),
            'ward_id' => Yii::t('app', 'ward'),
            'address' => Yii::t('app', 'address'),
            'isdefault' => Yii::t('app', 'default'),
        ];
    }

    public static function addAddress($address) {
        $model = new ShopAddress();
        $model->shop_id = $address->id;
        $model->shop_id = $address->id;
        $model->province_id = $address->province_id;
        $model->province_name = $address->province_name;
        $model->district_id = $address->district_id;
        $model->district_name = $address->district_name;
        $model->ward_id = $address->ward_id;
        $model->ward_name = $address->ward_name;
        $model->latlng = $address->latlng;
        $model->address = $address->address;
        $model->name_contact = $address->name_contact;
        $model->phone = $address->phone;
        $model->isdefault = 1;
        return $model->save();
    }

    public static function getByShop($shop_id) {
        return self::find()->where(['shop_id' => $shop_id])->all();
    }

    public static function getDefautByShop($shop_id) {
        $model = self::find()->where(['shop_id' => $shop_id, 'isdefault' => 1])->one();
        return $model ? $model : false;
    }

    public static function getAddressByOrderShop($order_shop) {
        if($order_shop['shop_adress_id']) {
            $model = self::findOne($order_shop['shop_adress_id']);
            if($model) {
                return $model;                
            } else {
                if($order_shop['shop_id']) {
                    return self::getDefautByShop($order_shop['shop_id']);
                }
            }
        }
        return false;
    }
}
