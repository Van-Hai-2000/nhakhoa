<?php

namespace common\models\product;

use common\components\ClaActiveRecordLog;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product_image".
 *
 * @property string $id
 * @property integer $product_id
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
class ProductImage extends ClaActiveRecordLog {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_image';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'path', 'name'], 'required'],
            [['product_id', 'height', 'width', 'created_at', 'is_avatar'], 'integer'],
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
            'product_id' => 'Id thủ thuật',
            'path' => 'Đường dẫn ảnh',
            'name' => 'Tên ảnh',
            'display_name' => 'Display Name',
            'height' => 'Chiều cao',
            'width' => 'Chiều rộng',
            'created_at' => 'Thời gian',
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
     * @param type $product_id
     * @param type $color
     * @return type
     */
    public static function getImagesByColor($product_id, $color) {
        $data = (new Query())->select('*')
                ->from('product_image')
                ->where('product_id=:product_id AND color=:color', [':product_id' => $product_id, ':color' => $color])
                ->all();
        return $data;
    }
    
    public static function getImagesById($product_id) {
        $data = (new Query())->select('*')
                ->from('product_image')
                ->where('product_id=:product_id ', [':product_id' => $product_id])
                ->one();
        return $data;
    }
    /**
     * get only image by color
     * @param type $product_id
     * @param type $color
     * @return type
     */
    public static function getImageByColor($product_id, $color) {
        $color = str_replace(' ', '', $color);
        $data = (new Query())->select('*')
                ->from('product_image')
                ->where('product_id=:product_id AND color=:color', [':product_id' => $product_id, ':color' => $color])
                ->one();
        return $data;
    }

}
