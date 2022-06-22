<?php

namespace common\models\gcacoin;

class Transfer extends \Yii\db\ActiveRecord
{
    public $user_id = '';
    public $value = '';
    public $user_receive = '';
    public $user_receive_search;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'value', 'user_receive'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['user_id', 'user_receive', 'value'], 'integer'],
            ['value', 'isAlias'],
            ['user_receive_search', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID',
            'value' => 'Số V cần chuyển',
            'user_receive' => 'ID tài khoản nhận',
            'user_receive_search' => 'Tài khoản nhận',
        ];
    }

    public function isAlias($attribute)
    {
        if ($this->$attribute % $this->config_money != 0) {
            $this->addError($attribute, 'Số tiền phải là bội số của 1');
            return false;
        } elseif ($this->$attribute <= 0) {
            $this->addError($attribute, 'Số tiền phải là số dương.');
            return false;
        }
        return true;
    }
}
