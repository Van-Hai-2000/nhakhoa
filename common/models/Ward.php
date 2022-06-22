<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "ward".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $latlng
 * @property integer $district_id
 */
class Ward extends \common\models\ActiveRecordC {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ward';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'type', 'latlng', 'district_id'], 'required'],
            [['district_id'], 'integer'],
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
            'district_id' => 'District ID',
        ];
    }

    /**
     * get data from district id
     * @param type $district_id
     * @return type
     */
    public static function dataFromDistrictId($district_id) {
        $data = (new Query())->select('*')
                ->from('ward')
                ->where('district_id=:district_id', [':district_id' => $district_id])
                ->all();
        return array_column($data, 'name', 'id');
    }

    public static function getNamebyId($id) {
        return ($w = Ward::findOne($id)) ?  $w->name : '';
    }

}
