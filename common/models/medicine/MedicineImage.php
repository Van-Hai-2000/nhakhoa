<?php

namespace common\models\medicine;

use common\components\ClaActiveRecordLog;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product_image".
 *
 * @property string $id
 * @property integer $medicine_id
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
class MedicineImage extends ClaActiveRecordLog {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'medicine_image';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_id', 'path', 'name'], 'required'],
            [['medicine_id', 'height', 'width', 'created_at', 'order', 'is_avatar'], 'integer'],
            [['path', 'name', 'display_name'], 'string', 'max' => 255],
            [['color','order'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'medicine_id' => 'Id thuốc - thiết bị',
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

    /**
     * get all images by color
     * @param type $medicine_id
     * @param type $color
     * @return type
     */
    public static function getImagesByColor($medicine_id, $color) {
        $data = (new Query())->select('*')
                ->from('medicine_image')
                ->where('medicine_id=:medicine_id AND color=:color', [':medicine_id' => $medicine_id, ':color' => $color])
                ->all();
        return $data;
    }
    
    public static function getImagesById($medicine_id) {
        $data = (new Query())->select('*')
                ->from('medicine_image')
                ->where('medicine_id=:medicine_id ', [':medicine_id' => $medicine_id])
                ->one();
        return $data;
    }
    /**
     * get only image by color
     * @param type $medicine_id
     * @param type $color
     * @return type
     */
    public static function getImageByColor($medicine_id, $color) {
        $color = str_replace(' ', '', $color);
        $data = (new Query())->select('*')
                ->from('medicine_image')
                ->where('medicine_id=:medicine_id AND color=:color', [':medicine_id' => $medicine_id, ':color' => $color])
                ->one();
        return $data;
    }

}
