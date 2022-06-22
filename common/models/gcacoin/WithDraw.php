<?php

namespace common\models\gcacoin;

use \Yii\behaviors\TimestampBehavior;
use Yii;

class WithDraw extends \common\models\ActiveRecordC
{
    public $config_money = 1;

    public static function tableName()
    {
        return 'withdraw';
    }

    function getTableName()
    {
        return 'withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'value', 'bank_id'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['user_id', 'admin_id', 'status', 'bank_id', 'created_at', 'updated_at', 'value'], 'integer'],
            ['value', 'isAlias'],
            [['last_coin'], 'number'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID',
            'value' => 'Số V cần rút',
            'bank_id' => 'Tài khoản đích',
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

    public static function optionsBank()
    {
        $user_id = Yii::$app->user->id;
        $query = 'SELECT user_bank.*, bank.name as bank_name FROM user_bank LEFT JOIN bank ON user_bank.bank_type = bank.id WHERE user_bank.user_id = ' . $user_id . '';
        $data = Yii::$app->db->createCommand($query)->queryAll();
        $dt[''] = Yii::t('app', 'select_bank');
        if (isset($data) && $data) {
            foreach ($data as $item) {
                $dt[$item['id']] = $item['bank_name'];
            }
        }
        return $dt;
    }

    function beforeSave($insert)
    {
        $bank_in = \common\models\user\UserBank::find()->where(['id' => $this->bank_id, 'user_id' => $this->user_id])->one();
        if ($bank_in && $bank = \common\models\Bank::findOne($bank_in->bank_type)) {
            $attrs = $bank_in->attributes;
            $attrs['bank_name'] = $bank->name;
            $this->bank_info = json_encode($attrs);
        } else {
            $this->addError('bank_id', 'Ngân hàng không tồn tại trong hệ thống.');
            return false;
        }
        return parent::beforeSave($insert);
    }

    function getBankInfo($attribute)
    {
        $data = $this->bank_info ? json_decode($this->bank_info, true) : [];
        return isset($data[$attribute]) ? $data[$attribute] : 'N/A';
    }

    function getMoney()
    {
        return \common\models\gcacoin\Gcacoin::getMoneyToCoin($this->value);
    }

    function getUser()
    {
        return $user = \frontend\models\User::findOne($this->user_id) ? $user : new \frontend\models\User();
    }
}
