<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "{{%product_register_info}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $email
 * @property string $phone
 * @property integer $product_id
 * @property integer $shop_id
 * @property integer $created_at
 * @property string $note
 */
class ProductRegisterInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_register_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'email', 'phone', 'product_id', 'shop_id', 'created_at'], 'required'],
            [['user_id', 'product_id', 'shop_id', 'created_at'], 'integer'],
            [['note'], 'string'],
            [['email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'email' => 'Email',
            'phone' => 'Phone',
            'product_id' => 'Product ID',
            'shop_id' => 'Shop ID',
            'created_at' => 'Created At',
            'note' => 'Note',
        ];
    }

    public static function getCheck($product_id) {
        \Yii::$app->session->open();
        if(!isset($_SESSION['check_register_product'][$product_id]) && !ProductRegisterInfo::find()->where(['product_id' =>$product_id, 'user_id' => Yii::$app->user->id])->one()) {
            return 0;
        }
        return 1;
    }
}
