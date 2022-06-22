<?php

namespace common\models\recruitment;

use Yii;

/**
 * This is the model class for table "benefit".
 *
 * @property string $id
 * @property string $name
 * @property string $icon_class
 * @property integer $order
 * @property integer $isinput
 * @property integer $status
 * @property string $created_at
 */
class Benefit extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'benefit';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'order'], 'required'],
            [['order', 'isinput', 'status', 'created_at'], 'integer'],
            [['name', 'icon_class'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên phúc lợi',
            'icon_class' => 'Icon Class',
            'order' => 'Thứ tự',
            'isinput' => 'Nhập trong tuyển dụng',
            'status' => 'Trạng thái',
            'created_at' => 'Created At',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

}
