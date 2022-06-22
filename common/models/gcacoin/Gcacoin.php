<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/20/2018
 * Time: 5:05 PM
 */

namespace common\models\gcacoin;

use yii\db\ActiveRecord;
use common\components\ClaGenerate;

class Gcacoin extends ActiveRecord
{
    public static function tableName()
    {
        return 'gca_coin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'gca_coin'], 'required', 'message' => '{attribute} không được bỏ trống'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'unique', 'message' => '{attribute} đã tồn tại'],
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
        ];
    }

    public function getCoin()
    {
        return $this->gca_coin ? (float)ClaGenerate::decrypt($this->gca_coin) : 0;
    }

    public function setCoin($coin)
    {
        return $this->gca_coin = ClaGenerate::encrypt($coin);
    }

    public function addCoin($coin)
    {
        $t = $coin + $this->getCoin();
        if ($t >= 0) {
            $this->setCoin($t);
            return true;
        }
        return false;
    }

    public function getCoinRed()
    {
        return $this->gca_coin_red ? (float)ClaGenerate::decrypt($this->gca_coin_red) : 0;
    }

    public function setCoinRed($coin)
    {
        return $this->gca_coin_red = ClaGenerate::encrypt($coin);
    }

    public function addCoinRed($coin)
    {
        $t = $coin + $this->getCoinRed();
        if (($t - $this->getCoinRedLock()) >= 0) {
            $this->setCoinRed($t);
            return true;
        }
        return false;
    }

    public function getCoinRedLock()
    {
        $count = (new \yii\db\Query())->from('withdraw')->select('SUM(value) as sum')->where(['user_id' => $this->user_id, 'status' => 0])->all();
        return isset($count[0]['sum']) ? $count[0]['sum'] : 0;
    }

    public function getCoinSale()
    {
        return $this->gca_coin_sale ? (float)ClaGenerate::decrypt($this->gca_coin_sale) : 0;
    }

    public function setCoinSale($coin)
    {
        return $this->gca_coin_sale = ClaGenerate::encrypt($coin);
    }

    public function addCoinSale($coin)
    {
        $t = $coin + $this->getCoinSale();
        if ($t >= 0) {
            $this->setCoinSale($t);
            return true;
        }
        return false;
    }

    public static function getCoinToMoney($money)
    {
        return $money / self::getPerMoneyCoin();
    }

    public static function getMoneyToCoin($money)
    {
        return $money * self::getPerMoneyCoin();
    }

    public function addMoney($money)
    {
        $coin = self::getCoinToMoney($money);
        if (($this->getCoin() + $coin) >= 0) {
            $this->addCoin($coin);
            return $this->save(false);
        }
        return 0;
    }

    public static function getPerMoneyCoin()
    {
        $config = \common\components\ClaLid::getConfigCoin();
        $sale = 1 - ($config->sale / 100);
        if ($config) {
            return $config ? ($config->money / $config->gcacoin) / $sale : 1;
        }
        return 1;
    }

    public static function getMoney($user_id)
    {
        $coin = \common\models\gcacoin\Gcacoin::findOne($user_id);
        $config = \common\components\ClaLid::getConfigCoin();
        if ($coin && $config) {
            return $coin->getCoin() ? $coin->getCoin() * $config->money / $config->gcacoin : 0;
        }
        return 0;
    }

    public static function getModel($id)
    {
        $model = self::findOne($id);
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->user_id = $id;
        $model->created_at = time();
        $model->updated_at = time();
        $model->gca_coin = ClaGenerate::encrypt(0);
        $model->gca_coin_red = ClaGenerate::encrypt(0);
        $model->gca_coin_sale = ClaGenerate::encrypt(0);
        $model->save();
        return $model;
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

    public static function addCoinByVpn($order)
    {
        $coin = Gcacoin::getCoinToMoney((float)$order->order_total);
        $gcoin = Gcacoin::getModel($order->user_id);
        $first_coin = $gcoin->getCoin();
        $string = 'Nap tiền thành công ' . formatMoney($coin) . ' V vào tài khoản';
        if ($gcoin->addCoin($coin)) {
            $history = new GcaCoinHistory();
            $history->user_id = $gcoin->user_id;
            $history->type = 'PAY_COIN_VPN';
            $history->data = $string;
            $history->gca_coin = $coin;
            $history->first_coin = $first_coin;
            $history->last_coin = $gcoin->getCoin();
            $history->save(false);
            //sen mail
            $user = \frontend\models\User::findOne($gcoin->user_id);
            if ($user && $user->email) {
                \common\models\mail\SendEmail::sendMail([
                    'email' => $user->email,
                    'title' => 'Nạp V thành công.',
                    'content' => 'Số dư thay đổi <b style="color: green"> ' . formatMoney($history->gca_coin) . '</b> OCOP V.  Số dư hiện tại: <b style="color: green">' . formatMoney($history->last_coin) . '</b>  OCOP V'
                ]);
            }
        }
    }

    static function addCoinAdmin($coin, $options = [])
    {
        $gcoin = \common\models\gcacoin\Gcacoin::getModel(\common\models\shop\Shop::ID_SHOP_BQT);
        $first_coin = $gcoin->getCoin();

        if ($gcoin->addCoin($coin) && $gcoin->save(false)) {
            $history = new \common\models\gcacoin\GcaCoinHistory();
            $history->user_id = $gcoin->user_id;
            $history->gca_coin = $coin;
            $history->first_coin = $first_coin;
            $history->last_coin = $gcoin->getCoin();
            $history->type = isset($options['type']) ? $options['type'] : 'ADD_COIN';
            $history->data = isset($options['note']) ? $options['note'] : 'Nhận coin';
            $history->save(false);
        }
    }

    static function addCoinCharity($coin, $options = [])
    {
        $gcoin = \common\models\gcacoin\Gcacoin::getModel(\common\models\shop\Shop::ID_SHOP_CHARITY);
        $first_coin = $gcoin->getCoin();
        if ($gcoin->addCoin($coin) && $gcoin->save(false)) {
            $history = new \common\models\gcacoin\GcaCoinHistory();
            $history->user_id = $gcoin->user_id;
            $history->gca_coin = $coin;
            $history->first_coin = $first_coin;
            $history->last_coin = $gcoin->getCoin();
            $history->type = isset($options['type']) ? $options['type'] : 'ADD_COIN';
            $history->data = isset($options['note']) ? $options['note'] : 'Nhận coin';
            $history->save(false);
        }
    }

    static function addCoinFeeTran($coin, $options = [])
    {
        $gcoin = \common\models\gcacoin\Gcacoin::getModel(\common\models\shop\Shop::ID_SHOP_FEETRAN);
        $first_coin = $gcoin->getCoin();
        if ($gcoin->addCoin($coin) && $gcoin->save(false)) {
            $history = new \common\models\gcacoin\GcaCoinHistory();
            $history->user_id = $gcoin->user_id;
            $history->gca_coin = $coin;
            $history->first_coin = $first_coin;
            $history->last_coin = $gcoin->getCoin();
            $history->type = isset($options['type']) ? $options['type'] : 'ADD_COIN';
            $history->data = isset($options['note']) ? $options['note'] : 'Nhận coin';
            $history->save(false);
        }
    }
}
