<?php

namespace common\models\transport;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "shop_transport".
 *
 * @property string $id
 * @property integer $shop_id
 * @property integer $transport_id
 * @property integer $status
 * @property integer $default
 * @property integer $created_at
 */
class ShopTransport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_transport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'transport_id', 'status', 'default'], 'required'],
            [['shop_id', 'transport_id', 'status', 'default'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'transport_id' => 'Transport ID',
            'status' => 'Status',
            'default' => 'Default',
        ];
    }

    public static function getByShop($shop_id) {
        $data = (new Query())
                ->select('st.*, t.name, t.note')
                ->from('shop_transport st')
                ->leftJoin('transport t', 't.id = st.transport_id')
                ->where(['t.status' => 1, 'st.shop_id' => $shop_id])
                ->all();
        return $data;
    }

    public static function getByShopOrder($shop_id, $list_product) {
        if(count($list_product) == 1) {
            foreach ($list_product as $product) {
                return (new Query())
                ->select('pt.*, t.name, t.note')
                ->from('product_transport pt')
                ->leftJoin('transport t', 't.id = pt.transport_id')
                ->where(['t.status' => 1, 'pt.product_id' => $product['product_id']])
                ->all();
            }
        }
        if($list_product) {
            foreach ($list_product as $product) {
                if($product['weight'] < 1 || $product['height'] < 1 || $product['width'] < 1 || $product['length'] < 1) {
                    return [];
                }
            }
        }
        return (new Query())
                ->select('st.*, t.name, t.note')
                ->from('shop_transport st')
                ->leftJoin('transport t', 't.id = st.transport_id')
                ->where(['t.status' => 1, 'st.shop_id' => $shop_id])
                ->all();
    }

    public static function getByShopLitte($shop_id) {
        $data = (new Query())
                ->from('shop_transport')
                ->where(['status' => 1, 'shop_id' => $shop_id])
                ->all();
        return $data;
    }

    public static function getByShopCreate($shop_id) {
        $tg = \common\models\transport\ProductTransport::deleteRaw($shop_id);
        $data = (new Query())
                ->select('st.*, t.name, t.note')
                ->from('shop_transport st')
                ->leftJoin('transport t', 't.id = st.transport_id')
                ->where(['t.status' => 1, 'st.shop_id' => $shop_id])
                ->all();
        return $data;
    }

    public static function getByShopAndTransport($shop_id, $transport_id) {
        return ShopTransport::find()->where(['shop_id' => $shop_id, 'transport_id' => $transport_id])->one();
    }

    public static function getDefault($shop_id) {
        return ShopTransport::find()->where(['shop_id' => $shop_id, 'default' =>1])->one();
    }
}
