<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_import".
 *
 * @property string $id
 * @property string $product_id
 * @property string $code
 * @property string $color
 * @property string $size
 * @property string $quantity
 * @property string $weight
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ProductImport extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_import';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code', 'color', 'size', 'quantity'], 'required'],
            [['product_id', 'quantity', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 20],
            [['size'], 'string', 'max' => 10],
            [['weight'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Sản phẩm',
            'code' => 'Mã sản phẩm',
            'color' => 'Màu sắc',
            'size' => 'Size',
            'quantity' => 'Số lượng',
            'weight' => 'Cân nặng',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert) {
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

    public static function getCurrent() {
        $data = (new \yii\db\Query())->select('*')
                ->from('product_import')
                ->where('status=:status', [':status' => \common\components\ClaLid::STATUS_ACTIVED])
                ->orderBy('id DESC')
                ->all();
        return $data;
    }

}
