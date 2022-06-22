<?php

namespace common\models\gcacoin;

use Yii;

/**
 * This is the model class for table "{{%gcacoin_confinement}}".
 *
 * @property string $id
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $shop_id
 * @property string $money
 * @property integer $hour
 * @property string $note
 * @property integer $created_at
 */
class CoinConfinement extends \common\components\ClaActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gcacoin_confinement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'shop_id', 'coin', 'hour', 'created_at'], 'required'],
            [['order_id', 'user_id', 'shop_id', 'hour', 'created_at'], 'integer'],
            [['coin'], 'number'],
            [['note'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order Shop ID',
            'user_id' => 'User ID',
            'shop_id' => 'Shop ID',
            'coin' => 'Money',
            'hour' => 'Hour',
            'note' => 'Note',
            'created_at' => 'Created At',
        ];
    }

    public static function addByOrder($order)
    {
        if ($order) {
            $hour = \common\models\gcacoin\Config::getHourConfinement();
            $model = new self();
            $model->order_id = $order->id;
            $model->user_id = $order->user_id;
            $model->shop_id = $order->shop_id;
            $model->coin =  $order->getCoinSalebyShop();
            $model->type_coin =  $order->getTypeCoin();
            $model->hour = $hour;
            $model->note = 'Tiền thanh toán đơn hàng' . $order->getOrderLabelId() . '. Thời gian tạm giữ : ' .  \common\components\ClaAll::getTextTime($hour);
            $model->created_at = time();
            if ($model->save(false)) {
                $shop = \common\models\shop\Shop::findOne($order->shop_id);
                $string = \common\components\ClaAll::getTextTimeTG($hour);
                if ($shop && $shop->email) {
                    \common\models\mail\SendEmail::sendMail([
                        'email' => $shop->email,
                        'title' => 'Đơn hàng ' . $order->getOrderLabelId() . ' đã được thành toán thành công',
                        'content' => '<p>Đơn hàng ' . $order->getOrderLabelId() . ' đã được thanh toán thành công.</p><p> Giá trị đơn hàng: ' . $order->getTextVByCoin($model->coin)  . '</p><p>' . $string . '</p>'
                    ]);
                }
                //send notification
                $noti = new \common\models\notifications\Notifications();
                $noti->title = 'Đơn hàng ' . $order->getOrderLabelId() . ' đã được thành toán thành công';
                $noti->description = 'Đơn hàng ' . $order->getOrderLabelId() . ' có giá trị: ' . $order->getTextVByCoin($model->coin) . ' đã được thành toán thành công.' . $string;
                $noti->link = \common\components\ClaUrl::to(['/management/gcacoin/confinement']);
                $noti->type = 3;
                $noti->recipient_id = $model->shop_id;
                $noti->unread = \common\components\ClaLid::STATUS_ACTIVED;
                $noti->save();
                return 1;
            }
        }
        return 0;
    }

    public static function cancerShopOrder($order)
    {
        if ($order->recostAffiliate()) {
            $model = self::find()->where(['order_id' => $order->id])->andWhere('status = 1')->one();
            if ($model) {
                $gca_coin = \common\models\gcacoin\Gcacoin::findOne($model->user_id);
                $coin = $model->getCoinCancer();
                $save = false;
                if ($order->isVSale()) {
                    $first_coin = $gca_coin->getCoinSale();
                    if ($gca_coin->addCoinSale($coin) && $gca_coin->save(false)) {
                        $save = true;
                        $last_coin = $gca_coin->getCoinSale();
                    }
                } else {
                    $first_coin = $gca_coin->getCoin();
                    if ($gca_coin->addCoin($coin) && $gca_coin->save(false)) {
                        $save = true;
                        $last_coin = $gca_coin->getCoin();
                    }
                }
                if ($save) {
                    $oid = $order->getOrderLabelId();
                    $model->status = 0;
                    $model->save(false);
                    $history = new GcaCoinHistory();
                    $history->user_id = $gca_coin->user_id;
                    $history->type = 'PAY_MEMBER_IN';
                    $history->data = 'Hoàn tiền do hủy đơn hàng ' . $oid;
                    $history->gca_coin = $coin;
                    $history->first_coin = $first_coin;
                    $history->last_coin = $last_coin;
                    $history->type_coin = $order->isVSale() ? GcaCoinHistory::TYPE_V_SALE : GcaCoinHistory::TYPE_V;
                    $history->save(false);
                    //sen mail
                    $_v_f = $order->getTextVByCoin($history->gca_coin);
                    $_v_l = $order->getTextVByCoin($history->last_coin);
                    $user = \frontend\models\User::findOne($gca_coin->user_id);
                    if ($user && $user->email) {
                        \common\models\mail\SendEmail::sendMail([
                            'email' => $user->email,
                            'title' => 'Hoàn tiền do hủy đơn hàng ' . $oid,
                            'content' => 'Số dư thay đổi <b style="color: green"> +' . $_v_f . '</b>.  Số dư hiện tại: <b style="color: green">' . $_v_l . '</b>'
                        ]);
                    }
                    //send notification
                    $noti = new \common\models\notifications\Notifications();
                    // $code = 'Mã đơn hàng <b style="color: green"> ' . $oid . '</b>';
                    $noti->title = 'Hoàn tiền do hủy đơn hàng ' . $oid;
                    $noti->description = 'Hoàn tiền do hủy đơn hàng ' . $oid . '. Số dư thay đổi <b style="color: green"> +' . $_v_f . '</b>.  Số dư hiện tại: <b style="color: green">' . $_v_l . '</b>';
                    $noti->link = \common\components\ClaUrl::to(['/management/gcacoin/index']);
                    $noti->type = 3;
                    $noti->recipient_id = $gca_coin->user_id;
                    $noti->unread = \common\components\ClaLid::STATUS_ACTIVED;
                    $noti->save();
                    return 1;
                }
                $siteinfo = \common\components\ClaLid::getSiteinfo();
                $email_manager = $siteinfo->email;
                if ($email_manager) {
                    \common\models\mail\SendEmail::sendMail([
                        'email' => $email_manager,
                        'title' => 'Hoàn tiền lỗi',
                        'content' => json_encode($model->attributes)
                    ]);
                }
                $order->costAffiliate();
                return 0;
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function getCoinCancer()
    {
        return $this->coin;
    }

    public static function getCoinConfinement($shop_id)
    {
        $money = (new \yii\db\Query())->select('SUM(coin) as sum')->from('gcacoin_confinement')->where(['shop_id' => $shop_id, 'status' => 1])->all();
        $money = (isset($money[0]['sum']) && $money[0]['sum'] > 0) ? $money[0]['sum'] : 0;
        return $money;
    }

    public static function getListUnconfinement()
    {
        return self::find()->leftJoin('order as os', 'os.id = gcacoin_confinement.order_id')->where('gcacoin_confinement.status = 1 AND os.status = 4 AND (os.updated_at + gcacoin_confinement.hour <= ' . time() . ') AND gcacoin_confinement.type_coin = ' . \common\models\gcacoin\GcaCoinHistory::TYPE_V)->limit(10)->all();
    }

    public static function getUseByShop($user_id = 0)
    {
        $user_id = $user_id ? $user_id : \Yii::$app->user->id;
        return (new \yii\db\Query())->select('gc.*, os.status as order_status, os.updated_at as order_updated_at')->from('gcacoin_confinement as gc')->leftJoin('order as os', 'os.id = gc.order_id')->where(['gc.status' => 1, 'gc.shop_id' => $user_id])->orderBy('id DESC')->all();
    }
}
