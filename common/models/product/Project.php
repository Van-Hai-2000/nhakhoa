<?php

namespace common\models\product;

use Yii;

class Project extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','province_id','district_id'], 'required'],
            [['created_at', 'updated_at','province_id','district_id','ward_id'], 'integer'],
            [['name','address'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên dự án',
            'province_id' => 'Tỉnh/ Thành phố',
            'district_id' => 'Quận/ Huyện',
            'ward_id' => 'Phường/ Xã',
            'address' => 'Địa chỉ',
            'created_at' => Yii::t('app', 'created_at'),
            'updated_at' => Yii::t('app', 'updated_at'),
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

    public static function optionProject()
    {
        $types = Project::find()->all();
        $data = ['' => '--- Chọn dự án ---'];
        if ($types) {
            foreach ($types as $type) {
                $data[$type['id']] = $type['name'];
            }
        }
        return $data;
    }

    public function optionsProjectByDistrict($district_id)
    {
        $data = Project::find()->where(['district_id' => $district_id])->all();
        $data = array_column($data,'name','id');
        return $data;
    }

}
