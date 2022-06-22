<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "district".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $latlng
 * @property integer $province_id
 */
class District extends \common\models\ActiveRecordC {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'district';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'type', 'latlng', 'province_id'], 'required'],
            [['province_id'], 'integer'],
            [['name', 'latlng'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'latlng' => 'Latlng',
            'province_id' => 'Province ID',
        ];
    }

    /**
     * get data from province id
     * @param type $province_id
     * @return type
     */
    public static function dataFromProvinceId($province_id) {
        $data = (new Query())->select('*')
                ->from('district')
                ->where('province_id=:province_id', [':province_id' => $province_id])
                ->orderBy('name ASC')
                ->all();
        return array_column($data, 'name', 'id');
    }

    public static function getNamebyId($id) {
        return ($d = District::findOne($id)) ?  $d->name : '';
    }

}
