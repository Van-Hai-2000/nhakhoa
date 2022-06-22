<?php

namespace common\models\user;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product_image".
 *
 * @property string $id
 * @property integer $medical_record_id
 * @property string $path
 * @property string $name
 * @property string $display_name
 * @property string $height
 * @property string $width
 * @property integer $order
 * @property string $created_at
 * @property string $color
 * @property integer $is_avatar
 */
class MedicalRecordBeforeImage extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'medical_record_before_image';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medical_record_id', 'path', 'name'], 'required'],
            [['medical_record_id', 'height', 'width', 'created_at', 'order'], 'integer'],
            [['path', 'name', 'display_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'medical_record_id' => 'ID',
            'path' => 'Path',
            'name' => 'Name',
            'display_name' => 'Display Name',
            'height' => 'Height',
            'width' => 'Width',
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

    public static function getImagesById($medical_record_id) {
        $data = (new Query())->select('*')
                ->from('medical_record_before_image')
                ->where('medical_record_id=:medical_record_id ', [':medical_record_id' => $medical_record_id])
                ->all();
        return $data;
    }

}
