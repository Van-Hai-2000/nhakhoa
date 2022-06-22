<?php

namespace common\models;

use Yii;
use yii\db\Query;
use common\components\ClaLid;
use common\models\user\UserAddress;

/**
 * This is the model class for table "province".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $latlng
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $position
 * @property boolean $show_in_home
 * @property integer $region
 * @property boolean $ishot
 * @property integer $order
 */
class Province extends \common\models\ActiveRecordC
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'latlng', 'avatar_path', 'avatar_name', 'region'], 'required'],
            [['position', 'region', 'order'], 'integer'],
            [['show_in_home', 'ishot'], 'boolean'],
            [['name', 'latlng'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 30],
            [['avatar_path', 'avatar_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên tỉnh/thành phố',
            'type' => 'Loại',
            'latlng' => 'Kinh độ/vĩ độ',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'position' => 'Position',
            'show_in_home' => 'Show In Home',
            'region' => 'Region',
            'ishot' => 'Ishot',
            'order' => 'Order',
        ];
    }

    /**
     * @hungtm
     * return array options province
     */
    public static function optionsProvince()
    {
        $province = (new self)->getAllCached();
        $data[''] = Yii::t('app', 'select_province');
        foreach ($province as $value) {
            $data[$value['id']] = $value['name'];
        }
        return $data;
    }

    public static function getNameProvince($ids)
    {
        $ids_array = explode(' ', $ids);
        $data = (new Query())->select('id, name')
            ->from('province')
            ->where('id IN (' . implode(',', $ids_array) . ')')
            ->all();
        return array_column($data, 'name', 'id');
    }

    public static function getNamebyId($id)
    {
        return ($province = Province::findOne($id)) ?  $province->name : '';
    }

    public static function getIdbyName($name)
    {
        return ($province = Province::find()->where(['LIKE', 'name', $name])->one()) ?  $province->id : 0;
    }

    /**
     * return array province
     * @param type $ids_string
     */
    public static function getProvincesByIds($ids_string)
    {
        $data = [];
        $ids = explode(' ', $ids_string);
        if (isset($ids) && count($ids)) {
            foreach ($ids as $id) {
                $province = ClaLid::getProvince($id);
                $data[] = $province;
            }
        }
        return $data;
    }

    public static function optionRegions()
    {
        $data = [
            '1' => 'Miền Bắc',
            '3' => 'Miền Trung',
            '5' => 'Tây Nguyên',
            '6' => 'Miền Nam',
            '8' => 'Miền Tây',
        ];
        return $data;
    }

    public static function getAllUserId($province_id)
    {
        $data = UserAddress::find()->select('user_id')->where(['province_id' => $province_id])->asArray()->column();
        return $data;
    }

    public static function getAllUserIdByRegionID($region_id)
    {
        $province_id = self::find()->select('id')->where(['region' => $region_id])->asArray()->column();
        if ($province_id) {
            $data = UserAddress::find()->select('user_id')->where(['province_id' => $province_id])->asArray()->column();
            return $data;
        }
        return [];
    }
}
