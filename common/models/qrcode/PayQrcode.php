<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/20/2018
 * Time: 5:05 PM
 */
namespace common\models\qrcode;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PayQrcode extends ActiveRecord
{
    public static function tableName() {
        return 'pay_qrcode';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['price','data'], 'safe'],
            [['price','default','value' => 0], 'safe'],
            [['type','token'], 'string'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

        ];
    }

}