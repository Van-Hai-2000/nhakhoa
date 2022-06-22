<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%notification_admin}}".
 *
 * @property integer $id
 * @property integer $account
 * @property integer $shop
 * @property integer $product
 * @property integer $order
 * @property integer $mail_contact
 */
class NotificationAdmin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification_admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account', 'shop', 'product', 'order', 'mail_contact'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account' => 'Account',
            'shop' => 'Shop',
            'product' => 'Product',
            'order' => 'Order',
            'mail_contact' => 'Mail Contact',
        ];
    }

    public static function addNotifcation($key) {
        $model = self::findOne(1);
        if ($model) {
            $model->$key = $model->$key ? $model->$key+1 : 1;
            $model->updated_at = time();
            $model->save(false);
        }
    }

    public static function removeNotifaction($key) {
        $model = self::findOne(1);
        if ($model) {
            $model->$key = 0;
            $model->updated_at = time();
            $model->save(false);
        }
    }
}
