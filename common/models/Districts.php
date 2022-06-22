<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%districts}}".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $latlng
 * @property integer $province_id
 * @property integer $id_ghn
 * @property string $name_ghn
 */
class Districts extends \common\components\ClaActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%districts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'type', 'latlng', 'province_id'], 'required'],
            [['id', 'province_id', 'id_ghn'], 'integer'],
            [['name', 'latlng', 'name_ghn'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 30],
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
            'type' => 'Type',
            'latlng' => 'Latlng',
            'province_id' => 'Province ID',
            'id_ghn' => 'Id Ghn',
            'name_ghn' => 'Name Ghn',
        ];
    }

    public static function findGhnId($name) {
        if($model = Districts::find()->where(['name' => $name])->one()) {
            return $model->id_ghn;
        }
        return 0;
    }
}
