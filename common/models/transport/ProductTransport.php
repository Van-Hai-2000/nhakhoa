<?php

namespace common\models\transport;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "{{%product_transport}}".
 *
 * @property string $id
 * @property integer $product_id
 * @property integer $transport
 * @property integer $status
 * @property integer $default
 */
class ProductTransport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_transport}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'transport_id', 'status', 'default'], 'required'],
            [['product_id', 'transport_id', 'status', 'default'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'transport_id' => 'Transport_id',
            'status' => 'Status',
            'default' => 'Default',
        ];
    }

    public static function getByProduct($product_id) {
        $data = (new Query())
                ->select('st.*, t.name, t.note')
                ->from('product_transport st')
                ->leftJoin('transport t', 't.id = st.transport_id')
                ->where(['t.status' => 1, 'st.product_id' => $product_id])
                ->all();
        return $data;
    }

    public static function getByProductAndTransport($product_id, $transport_id) {
        return ProductTransport::find()->where(['product_id' => $product_id, 'transport_id' => $transport_id])->one();
    }

    public static function deleteRaw($user_id)
    {
        return \Yii::$app->db
            ->createCommand()
            ->delete('product_transport', ['product_id' => $user_id, 'status' => 2])
            ->execute();
    }

    public static function setDefaultZero($product_id)
    {
        ProductTransport::updateAll(
                        ['default' => 0], [ 
                        'default' => 1, 
                        'product_id' => $product_id
                    ]);
    }
    public static function createdInShop($product_id, $shop_id)
    {
        $all = ShopTransport::getByShopLitte($shop_id);
        foreach ($all as $transport) {
            if(!($model = ProductTransport::find()->where(['product_id' => $product_id, 'transport_id' => $transport['transport_id']])->one())) {
                $model = new ProductTransport();
                $model->status =1;
                $model->product_id = $product_id;
                $model->transport_id = $transport['transport_id'];
                $model->default = 0;
                if(!$model->save()) {
                    return 0;
                }
            }
        }
        return 1;
    }

    public static function deleteInShop($shop_id)
    {
       ProductTransport::deleteAll("product_id = $shop_id AND transport_id <> 0 ");
    }

    public static function getDefault($product_id) {
        return self::find()->where(['product_id' => $product_id, 'default' =>1, 'status' => 1])->one();
    }
}
