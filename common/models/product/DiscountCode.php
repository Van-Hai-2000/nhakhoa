<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "discount_code".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $time_start
 * @property integer $time_end
 * @property integer $type_discount
 * @property string $value
 * @property integer $count
 * @property integer $user_use
 * @property integer $created_at
 * @property integer $status
 */
class DiscountCode extends \common\models\ActiveRecordC
{

    const TYPE_PERCENT = 2;
    const TYPE_DISCOUNT = 1;

    public $_products = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount_code';
    }

    static function optionType()
    {
        return [
            self::TYPE_DISCOUNT => 'Giảm trực tiếp số tiền',
            self::TYPE_PERCENT => 'Giảm phần trăm so với tổng đơn hàng mua',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'time_start', 'time_end', 'type_discount', 'count', 'count_limit', 'user_use', 'created_at', 'status'], 'integer'],
            [['value'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Code',
            'shop_id' => 'Doanh nghiệp',
            'time_start' => 'Thời gian bắt đầu',
            'time_end' => 'Thời gian hết hạn',
            'type_discount' => 'Loại giảm giá',
            'value' => 'Giá trị:(đ hoặc % theo loại)',
            'status' => 'Trạng thái',
            'count' => 'Số lần đã sử dụng',
            'count_limit' => 'Số lần sử dụng/mã',
            'user_use' => 'User Use',
            'created_at' => 'Created At',
        ];
    }

    public static function loadShowAll()
    {
        $model = new self();
        $tg = \common\models\product\Product::find()->where(['shop_id' => Yii::$app->user->id])->all();
        if ($tg) {
            foreach ($tg as $value) {
                $model->_products[$value['id']] = $value;
            }
        }
        return $model;
    }

    public static function loadShowAllApi($user_id)
    {
        $model = new self();
        $tg = \common\models\product\Product::find()->where(['shop_id' => $user_id])->all();
        if ($tg) {
            foreach ($tg as $value) {
                $model->_products[$value['id']] = $value;
            }
        }
        return $model;
    }

    public function show($attribute)
    {
        switch ($attribute) {
            case 'shop_id':
                return ($tg = \common\models\shop\Shop::findOne($this->$attribute)) ? $tg->name : $this->$attribute;
                break;
            case 'time_start':
                return $this->$attribute > 0 ? date('d-m-Y H:i', $this->$attribute) : '';
                break;
            case 'time_end':
                return $this->$attribute > 0 ? date('d-m-Y H:i', $this->$attribute) : '';
                break;
            case 'type_discount':
                $tg = self::optionType();
                return isset($tg[$this->$attribute]) ? $tg[$this->$attribute] : $this->$attribute;
                break;
            case 'status':
                return $this->$attribute ? 'Chưa dùng' : 'Đã dùng';
                break;
        }
        return parent::show($attribute);
    }

    function setAttributeShow($attr)
    {
        $this->setAttributes($attr, false);
    }

    public function showValue()
    {
        return ($this->type_discount == 1) ? formatMoney($this->value) . ' ' . Yii::t('app', 'currency') : $this->value . ' %';
    }

    public function showProducts()
    {
        if ($this->all == 1) {
            return 'Tất cả sản phẩm';
        }
        $products = explode(' ', $this->products);
        $string = [];
        foreach ($products as $value) {
            if (isset($this->_products[$value])) {
                $string[] = $this->_products[$value]['name'];
            }
        }
        return $string ? implode(', ', $string) : '';
    }

    public static function checkCodeOrder($shop_id, $products, $code)
    {
        if (!$products) {
            return false;
        }
        foreach ($products as $id) {
            if ($tg = self::checkCode($shop_id, $id, $code)) {
                return $tg;
            }
        }
        return false;
    }

    public static function checkCode($shop_id, $product_id, $code)
    {
        $time = time();
        $item = self::find()->where("id = '$code' AND status = 1 AND shop_id = '$shop_id' AND time_start <= '$time' AND time_end >= '$time'")->one();
        if ($item) {
            if ($item->all == 1) {
                return $item;
            } else {
                $products = explode(' ', $item->products);
                return in_array($product_id, $products) ? $item : false;
            }
        }
        return false;
    }

    public function getSaleMoney($items)
    {
        if ($this->type_discount == self::TYPE_DISCOUNT) {
            return $this->value;
        }
        $products = [];
        if ($this->all == 1) {
            $products = $items;
        } else {
            $ids = explode(' ', $this->products);
            foreach ($items as $item) {
                if (in_array($item['product_id'], $ids)) {
                    $products[] = $item;
                }
            }
        }
        $total = 0;
        if ($products) foreach ($products as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total * $this->value / 100;
    }

    public function addOderId($id)
    {
        $this->order_id = $this->order_id ?  $this->order_id . ' ' . $id : $id;
        return $this->order_id;
    }
}
