<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "{{%shop_images}}".
 *
 * @property integer $img_id
 * @property integer $shop_id
 * @property string $name
 * @property string $path
 * @property string $display_name
 * @property string $alias
 * @property integer $user_id
 * @property integer $height
 * @property integer $width
 * @property integer $created_time
 * @property string $order
 * @property integer $type
 */
class ShopImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_images}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'height', 'width', 'created_at', 'order', 'type'], 'integer'],
            [['name', 'path', 'display_name', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'img_id' => 'Img ID',
            'shop_id' => 'Shop ID',
            'name' => 'Name',
            'path' => 'Path',
            'display_name' => 'Display Name',
            'alias' => 'Alias',
            'user_id' => 'User ID',
            'height' => 'Height',
            'width' => 'Width',
            'created_at' => 'Created Time',
            'order' => 'Order',
            'type' => 'Type',
        ];
    }

    public static function getImages($id) {
        return ShopImages::find()->where(['shop_id' => $id, 'type' => 1])->all();
    }

    public static function getImageAuths($id) {
        return ShopImages::find()->where(['shop_id' => $id, 'type' => Shop::IMG_AUTH])->all();
    }
}
