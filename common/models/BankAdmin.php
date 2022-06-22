<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bank_admin}}".
 *
 * @property integer $id
 * @property string $bank_name
 * @property string $number
 * @property string $user_name
 * @property string $address
 * @property integer $isdefault
 * @property integer $created_at
 * @property integer $updated_at
 */
class BankAdmin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bank_admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isdefault', 'created_at', 'updated_at'], 'integer'],
            [['bank_name'], 'string', 'max' => 100],
            [['number', 'user_name'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bank_name' => 'Bank Name',
            'number' => 'Number',
            'user_name' => 'User Name',
            'address' => 'Address',
            'isdefault' => 'Isdefault',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
