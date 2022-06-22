<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/20/2018
 * Time: 5:05 PM
 */

namespace common\models\gcacoin;

use yii\behaviors\TimestampBehavior;

class GcaCoinHistory extends \common\models\ActiveRecordC
{

    const TYPE_V_SALE = 2;
    const TYPE_V_RED = 1;
    const TYPE_V = 0;

    const AFFILLATE_APP = 'AFFILLATE_APP';

    public static function tableName()
    {
        return 'gca_coin_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'gca_coin'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['gca_coin', 'first_coin', 'last_coin'], 'number'],
        ];
    }

    // public function behaviors()
    // {
    //     return [
    //         TimestampBehavior::className(),
    //     ];
    // }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gca_coin' => 'Số V',
        ];
    }

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    function getTypeCoin()
    {
        switch ($this->type_coin) {
            case 1:
                return __VOUCHER_RED;
            case 2:
                return __VOUCHER_SALE;
        }
        return __VOUCHER;
    }

    function getTextCoin($coin)
    {
        return formatCoin($coin) . ' ' . $this->getTypeCoin();
    }
}
