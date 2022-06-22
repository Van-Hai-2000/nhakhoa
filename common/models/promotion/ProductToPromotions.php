<?php

namespace common\models\promotion;
use yii\db\Query;
use Yii;
use common\components\ClaLid;

/**
 * This is the model class for table "{{%product_to_promotions}}".
 *
 * @property integer $id
 * @property integer $promotion_id
 * @property integer $product_id
 * @property integer $created_time
 */
class ProductToPromotions extends \yii\db\ActiveRecord
{
    const DEFAULT_ORDER = ' id DESC ';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_to_promotions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_id', 'product_id', 'created_time'], 'integer'],
            [['promotion_id', 'product_id', 'hour_space_start'], 'unique', 'targetAttribute' => ['promotion_id', 'product_id', 'hour_space_start'], 'message' => 'The combination of Promotion ID and Product ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promotion_id' => 'Promotion ID',
            'product_id' => 'Product ID',
            'created_time' => 'Created Time',
        ];
    }

    public static function getByPromotionId($promotion_id) {
        $data = (new Query())
                ->select('p.name as product_name, t.*')
                ->from('product_to_promotions t')
                ->rightJoin('product p', 't.product_id = p.id')
                ->where(['promotion_id' => $promotion_id])
                ->all();
        return $data;
    }

    public static function getProductByAttr($options = []) {

        $where = "t.status = 1";
        if (isset($options['attr']) && $options['attr']) {
            foreach ($options['attr'] as $key => $value) {
                if(is_array($value)) {
                    // echo implode(',', $value).'---';
                    $where .= " AND $key IN(".implode(',', $value).")";
                } else {
                    $where .= " AND $key = '$value' ";
                }
            }
        }
        $order = ProductToPromotions::DEFAULT_ORDER;
        if (isset($options['order']) && $options['order']) {
            $order = $options['order'];
        }

        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }

        if (isset($options['page'])) {
            $offset = ($options['page'] - 1) * $limit;
        } else {
            $offset = 0;
        }
        //
        if (isset($count)) {
            return (new Query())->select('p.*, pt.price as price_promotion_sale, pt.quantity as quantity_promotion_sale, pt.quantity_selled as quantity_selled_promotion_sale, pt.id as id_promotion_sale, pt.hour_space_start')
                ->from('product_to_promotions pt,')
                ->rightJoin('product p', 'p.id = pt.product_id')
                ->leftJoin('promotions t', 't.id = pt.promotion_id')
                ->leftJoin('user u', 'p.shop_id = u.id')
                ->where($where)
                ->count();
        }
        $products = (new Query())->select('p.*, pt.price as price_promotion_sale, pt.quantity as quantity_promotion_sale, pt.quantity_selled as quantity_selled_promotion_sale, pt.id as id_promotion_sale, pt.hour_space_start')
                ->from('product_to_promotions pt,')
                ->rightJoin('product p', 'p.id = pt.product_id')
                ->leftJoin('promotions t', 't.id = pt.promotion_id')
                ->leftJoin('user u', 'p.shop_id = u.id')
                ->where($where)
                ->orderBy($order)
                ->limit($limit)
                ->offset($offset)
                ->all();
        return $products;
    }

    public static function getPromotionItemNowByProduct($product_id) {
        $promotion = \common\models\promotion\Promotions::getPromotionNow();
        if($promotion) {
            $hour = $promotion->getHourNow();
            return self::find()->where(['product_id' => $product_id, 'hour_space_start' => $hour, 'promotion_id' => $promotion->id])->andWhere('quantity_selled < quantity')->one();
        }
        return null;
    }

    public static function getPromotionItemByProduct($product_id) {
        $promotion = \common\models\promotion\Promotions::getPromotionNow();
        if($promotion) {
            $hour = $promotion->getHourNow();
            return self::find()->where(['product_id' => $product_id, 'promotion_id' => $promotion->id])->andWhere('quantity_selled < quantity')->one();
        }
        return null;
    }

    public static function getTimeSpaceStart($hour, $date) {
        if(!$date) {
            return 0;
        }
        date_default_timezone_set("Asia/Bangkok");
        $hour = $hour ? $hour : 0;
        $date = $date ? $date : date('d-m-Y', time());
        return strtotime($hour.':00 '.$date);
    }

    public static function setQuantitySelled($product_id, $quantity, $price) {
        $items = self::find()->where(['product_id' => $product_id, 'price' => $price])->all();
        if($items) {
            foreach ($items as $item) {
                $item->quantity_selled += $quantity;
                $item->quantity_selled = $item->quantity_selled < 0 ? 0 : ($item->quantity_selled > $item->quantity ? $item->quantity : $item->quantity_selled);
                $item->save();
            }
        }
    }

    public static function getPromotionInNow($promotion) {
        $where = "t.status = 1";
        $hour_now = $promotion->getHourNow(); 
        //
        $products = (new Query())
                ->from('product_to_promotions')
                ->where(['hour_space_start' => $hour_now, 'promotion_id' => $promotion->id])
                ->orderBy('id DESC')
                ->all();
        return $products;
    }
}
