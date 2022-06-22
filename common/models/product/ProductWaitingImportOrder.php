<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_waiting_import_order".
 *
 * @property string $id
 * @property string $product_id
 * @property integer $brand
 * @property string $price
 * @property string $code
 * @property string $color
 * @property string $size
 * @property string $quantity
 * @property string $created_at
 * @property string $updated_at
 * @property integer $order_id
 * @property integer $order_item_id
 */
class ProductWaitingImportOrder extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_waiting_import_order';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_id', 'code', 'color', 'size'], 'required'],
            [['product_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 20],
            [['size'], 'string', 'max' => 10],
            [['brand', 'price', 'order_id', 'order_item_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Sản phẩm',
            'code' => 'Mã gốc',
            'color' => 'Màu',
            'size' => 'Size',
            'quantity' => 'Số lượng nhập',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày sửa',
            'brand' => 'Thương hiệu',
            'price' => 'Giá',
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

    public static function getAllData($brand) {
        if ($brand == 0) {
            $data = (new \yii\db\Query())->select('*')
                    ->from('product_waiting_import_order')
                    ->orderBy('created_at DESC')
                    ->all();
        } else {
            $data = (new \yii\db\Query())->select('*')
                    ->from('product_waiting_import_order')
                    ->where('brand=:brand', [':brand' => $brand])
                    ->orderBy('created_at DESC')
                    ->all();
        }
        return $data;
    }

    public static function getTotalPriceByBrand($brand) {
        $data = $data = (new \yii\db\Query())->select('*')
                ->from('product_waiting_import_order')
                ->where('brand=:brand', [':brand' => $brand])
                ->orderBy('created_at DESC')
                ->all();
        $total = 0;
        if ($data) {
            foreach ($data as $item) {
                $total += $item['price'];
            }
        }
        return $total;
    }

    public static function countByOrderId($order_id) {
        $count = (new \yii\db\Query())->select('*')
                ->from('product_waiting_import_order')
                ->where('order_id=:order_id', [':order_id' => $order_id])
                ->count();
        return $count;
    }

    public static function checkExist($item) {
        $data = ProductWaitingImportOrder::find()
                        ->where('order_id=:order_id AND order_item_id=:order_item_id', [
                            ':order_id' => $item['order_id'],
                            ':order_item_id' => $item['id']
                        ])->one();
        if (!$data) {
            $data = ProductWaitingImportStock::find()
                            ->where('order_id=:order_id AND order_item_id=:order_item_id', [
                                ':order_id' => $item['order_id'],
                                ':order_item_id' => $item['id']
                            ])->one();
        }
        return $data;
    }

}
