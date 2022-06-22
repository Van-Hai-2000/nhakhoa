<?php

namespace common\models\sale;

use common\models\branch\Branch;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "branch_sales".
 *
 * @property integer $id
 * @property integer $branch_id
 * @property string $branch_name
 * @property double $money
 * @property integer $type
 * @property integer $type_id
 * @property integer $week
 * @property integer $month
 * @property integer $year
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $payment_id
 */
class BranchSalesV2 extends \yii\db\ActiveRecord
{
    const TYPE_BENHAN = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch_sales_v2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id', 'money'], 'required'],
            [['branch_id', 'type', 'type_id', 'week', 'month', 'year', 'created_at', 'updated_at','payment_id'], 'integer'],
            [['money'], 'number'],
            [['branch_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Chi nhánh',
            'branch_name' => 'Chi nhánh',
            'money' => 'Doanh số',
            'type' => 'Type',
            'type_id' => 'Type ID',
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

    public function getBranch(){
        return $this->hasOne(Branch::className(),['id' => 'branch_id']);
    }
}
