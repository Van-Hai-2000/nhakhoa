<?php

namespace common\models\sale;

use Yii;

/**
 * This is the model class for table "customer_sales".
 *
 * @property string $id
 * @property string $user_id
 * @property string $quantity
 * @property integer $week
 * @property integer $month
 * @property integer $year
 * @property integer $created_at
 * @property integer $updated_at
 */
class CustomerSales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_sales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'quantity', 'month', 'year', 'created_at', 'updated_at'], 'integer'],
            [['key'], 'string', 'max' => 255],
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
            'quantity' => 'Quantity',
            'week' => 'Week',
            'month' => 'Month',
            'year' => 'Year',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if($this->created_at){
                    $this->updated_at =  $this->created_at;
                }else{
                    $this->created_at = $this->updated_at = time();
                }
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }
}
