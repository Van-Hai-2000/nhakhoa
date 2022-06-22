<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "order_shop".
 *
 * @property string $id
 * @property string $user_id
 * @property string $order_id
 * @property string $shipfee
 * @property string $order_total
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class OrderShop extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'order_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['shipfee', 'order_total'], 'number'],
            [['transport_id', 'transport_type'], 'safe'],
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
            'order_id' => 'Order ID',
            'shipfee' => 'Shipfee',
            'order_total' => 'Order Total',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    public static function getInShopByStatus($id, $status)
    {
        $data = (new \yii\db\Query())->select('o.*, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name, s.name as s_name, s.alias as s_alias, or.payment_status, or.payment_method')
            ->from('order_shop o')
            ->leftJoin('shop s', 's.id = o.shop_id')
            ->rightJoin('order or', 'or.id = o.order_id')
            ->where(['o.shop_id' => $id, 'o.status' => $status])
            ->orderBy('id DESC')
            ->all();
        return $data;
    }

    public static function getInShopByStatusCount($id, $status)
    {
        $data = (new \yii\db\Query())
            ->from('order_shop o')
            ->leftJoin('shop s', 's.id = o.shop_id')
            // ->leftJoin('order or', 'or.id = o.order_id')
            ->where(['o.shop_id' => $id, 'o.status' => $status])
            ->count();
        return $data;
    }

    public static function getByUserByStatus($id, $status)
    {
        $data = (new \yii\db\Query())->select('o.*, s.avatar_path as s_avatar_path, s.avatar_name as s_avatar_name, s.name as s_name, s.alias as s_alias, or.payment_status, or.payment_method')
            ->from('order_shop o')
            ->leftJoin('shop s', 's.id = o.shop_id')
            ->rightJoin('order or', 'or.id = o.order_id')
            ->where(['o.user_id' => $id, 'o.status' => $status])
            ->orderBy('id DESC')
            ->all();
        return $data;
    }

    public static function getByUserByStatusCount($id, $status)
    {
        $data = (new \yii\db\Query())
            ->from('order_shop o')
            ->join('LEFT JOIN', 'shop s', 's.id = o.shop_id')
            ->where(['o.user_id' => $id, 'o.status' => $status])
            ->count();
        return $data;
    }


    public static function getDetail($id)
    {
        $data = (new \yii\db\Query())->select('os.*, o.name, o.phone, o.email, o.address,  s.phone as shop_phone, s.name_contact, o.payment_status, o.payment_method, o.phone as user_phone')
            ->from('order_shop os')
            ->rightJoin('order o', 'o.id = os.order_id')
            ->leftJoin('shop s', 's.id = os.shop_id')
            ->where(['os.id' => $id])
            ->one();
        return $data;
    }

    public static function getInShopById($id)
    {
        return OrderShop::find()->where(['id' => $id, 'shop_id' => Yii::$app->user->id])->one();
    }

    public static function getInUserById($id)
    {
        return OrderShop::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one();
    }

    public static function getByOrderId($id)
    {
        return OrderShop::find()->where(['order_id' => $id])->all();
    }

    // public static function updateStatus($order_shop_id) {
    //     date_default_timezone_set('Asia/Ho_Chi_Minh');
    //     $order_shop = self::findOne($order_shop_id);
    //     $data = [
    //         'order_id' => $order_shop_id,
    //         'time' => time(),
    //         'status' => \commom\models\order\Order::ORDER_CANCEL,
    //         'type' => $order_shop->transport_type,
    //         'content' => Yii::t('app', 'update_auto_ghn'),
    //         'created_at' => time()
    //     ];
    //     return \common\models\order\OrderShopHistory::saveData($data);
    // }

    public static function updateStatusAuto($order_shop_id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $order_shop = self::findOne($order_shop_id);
        $tru = false;
        if ($order_shop && $order_shop->status != 0) {
            if ($order_shop->status > 1) {
                $tru = true;
            }
            $history = \common\models\order\OrderShopHistory::getLast($order_shop_id);
            $claShipping = new \common\components\shipping\ClaShipping();
            $claShipping->loadVendor(['method' => $order_shop->transport_type]);
            $options['data']['OrderCode'] = $order_shop->transport_id;
            $info = $claShipping->getInfoOrderUpdate($options);
            // $info['time'] = '22-06-2018';
            if ($info['time'] && $info['status']) {
                $time = strtotime($info['time']);
                $status = \common\models\order\OrderShopHistory::getStatusId($info['status'], $order_shop->transport_type);
                if ($status != $history['status']) {
                    $data = [
                        'order_id' => $order_shop_id,
                        'time' => $time,
                        'status' => $status,
                        'type' => $order_shop->transport_type,
                        'content' => Yii::t('app', 'update_auto_ghn'),
                        'created_at' => time()
                    ];
                    $statustg = \common\models\order\OrderShopHistory::getSystemStatus($status, $order_shop->transport_type);
                    if ($statustg != $order_shop->status) {
                        $order_shop->status = $statustg;
                        if ($order_shop->save()) {
                            if ($order_shop->status == 2) {
                                $order_shop->setPromotion();
                            }
                            if ($order_shop->status == 0 && $tru) {
                                if (\common\models\gcacoin\CoinConfinement::cancerShopOrder($order_shop)) {
                                    $order_shop->setPromotion(0);
                                }
                            }
                            $sve = \common\models\notifications\Notifications::updateStatusOrder($order_shop);
                            return \common\models\order\OrderShopHistory::saveData($data);
                        }
                    } else {
                        return \common\models\order\OrderShopHistory::saveData($data);
                    }
                }
            }
            return false;
        }
    }

    public static function findByTransportId($transport_id)
    {
        return self::find()->where(['transport_id' => $transport_id])->one();
    }

    public static function deleteByOrder($order_id)
    {
        OrderShop::updateAll(
            ['status' => 9],
            ['order_id' => $order_id]
        );
    }

    public function beforeDelete()
    {
        if ($this->transport_id && $this->status != 0) {
            return false;
        }
        return parent::beforeDelete();
    }

    public function setPromotion($booleen = 1)
    {
        if ($this->id) {
            $items = \common\models\order\OrderItem::find()->where(['order_shop_id' => $this->id])->all();
            if ($items) {
                foreach ($items as $item) {
                    \common\models\promotion\ProductToPromotions::setQuantitySelled($item->product_id, ($booleen ? $item->quantity : (-$item->quantity)), $item->price);
                }
            }
        }
    }

    public function getShopAddressSlected($id_selected = false)
    {
        $shop_id = $this->shop_id;
        if (!$shop_id) {
            $this->address_id = 0;
            return false;
        }
        $location_shop = \common\models\shop\ShopAddress::getByShop($shop_id);
        if ($id_selected === false) {
            $id_selected = \common\components\ClaCookie::getValueCookieShopAddress($this->shop_id);
        }
        if (!($id_selected === false)) {
            foreach ($location_shop as $item) {
                if ($id_selected == $item['id']) {
                    $this->address_id = $id_selected;
                    return true;
                }
            }
        } else {
            foreach ($location_shop as $item) {
                if ($item['isdefault']) {
                    $this->address_id = $item['id'];
                    return true;
                }
            }
        }
        $this->address_id = 0;
        return false;
    }

    public function getSaleAffBuyCoin()
    {
        $sum = (new \yii\db\Query())->from('order_item')->select(" SUM(sale_buy_shop_coin) as sum ")->where(['order_shop_id' => $this->id])->all();
        if ($sum && isset($sum[0]['sum'])) {
            return $sum[0]['sum'];
        }
        return 0;
    }

    public function getSaleAffSale()
    {
        $sum = (new \yii\db\Query())->from('order_item')->select(" SUM(sale) as sum ")->where(['order_shop_id' => $this->id])->all();
        if ($sum && isset($sum[0]['sum'])) {
            return $sum[0]['sum'];
        }
        return 0;
    }

    public function getCoinSalebyShop()
    {
        $order_total = \common\models\gcacoin\Gcacoin::getCoinToMoney($this->order_total);
        if ($order_total > 0) {
            return $order_total - $this->getSaleAffBuyCoin();
        }
        return $order_total;
    }

    public function costAffiliate($order_items = [])
    {
        $coin = $this->getCostAffiliate($order_items);
        if ($coin > 0) {
            $user_id = $this->shop_id;
            $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
            $first_coin = $gca_coin->getCoin();
            if ($gca_coin->addCoin(-$coin) && $gca_coin->save(false)) {
                $history = new \common\models\gcacoin\GcaCoinHistory();
                $history->user_id = $user_id;
                $history->type = 'COST_AFFILIATE';
                $history->data = 'Thanh toán phí affiliate đơn hàng OR' . $this->order_id;
                $history->gca_coin = -$coin;
                $history->first_coin = $first_coin;
                $history->last_coin = $gca_coin->getCoin();
                $history->save(false);
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public function recostAffiliate($order_items = [])
    {
        $coin = $this->getCostAffiliate($order_items);
        if ($coin > 0) {
            $user_id = $this->shop_id;
            $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
            $first_coin = $gca_coin->getCoin();
            if ($gca_coin->addCoin($coin) && $gca_coin->save(false)) {
                $history = new \common\models\gcacoin\GcaCoinHistory();
                $history->user_id = $user_id;
                $history->type = 'RECOST_AFFILIATE';
                $history->data = 'Hoàn phí affiliate đơn hàng OR' . $this->order_id;
                $history->gca_coin = $coin;
                $history->first_coin = $first_coin;
                $history->last_coin = $gca_coin->getCoin();
                $history->save(false);
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public function getCostAffiliate($order_items = [])
    {
        $order_items = $order_items ? $order_items : \common\models\order\OrderItem::find()->where(['order_shop_id' => $this->id])->all();
        $coin = 0;
        if ($order_items) {
            foreach ($order_items as $ot) {
                if ($ot->user_before_product > 0 && $ot->sale_before_product > 0) {
                    $coin += $ot->sale_before_product;
                }
                if ($ot->user_before_shop > 0 && $ot->sale_before_shop > 0) {
                    $coin += $ot->sale_before_shop;
                }
                if ($ot->sale_charity > 0) {
                    $coin += $ot->sale_charity;
                }
                if ($ot->sale_admin > 0) {
                    $coin += $ot->sale_admin;
                }
            }
        }
        return $coin;
    }

    public function addAffiliate()
    {
        if ($this->status_check_cancer != \common\components\ClaLid::STATUS_ACTIVED) {
            $this->status_check_cancer = \common\components\ClaLid::STATUS_ACTIVED;
            $this->time_check_cancer = time();
            $this->save();
            $order_items = \common\models\order\OrderItem::find()->where(['order_shop_id' => $this->id])->all();
            $addmoney = [];
            if ($order_items) {
                foreach ($order_items as $ot) {
                    if ($ot->user_before_product > 0 && $ot->sale_before_product > 0) {
                        if (!isset($addmoney[$ot->user_before_product])) {
                            $addmoney[$ot->user_before_product]['coin'] = 0;
                            $addmoney[$ot->user_before_product]['note'] = '';
                        }
                        $addmoney[$ot->user_before_product]['coin'] += $ot->sale_before_product;
                        $addmoney[$ot->user_before_product]['note'] .= 'Thưởng giới thiệu sản phẩm ID' . $ot->product_id . ' ' . $ot->sale_before_product . ' V trong đơn hàng OR' . $this->order_id . '. ';
                    }
                    if ($ot->user_before_shop > 0 && $ot->sale_before_shop > 0) {

                        if (!isset($addmoney[$ot->user_before_shop])) {
                            $addmoney[$ot->user_before_shop]['coin'] = 0;
                            $addmoney[$ot->user_before_shop]['note'] = '';
                        }

                        $addmoney[$ot->user_before_shop]['coin'] += $ot->sale_before_shop;

                        $addmoney[$ot->user_before_shop]['note'] .= 'Thưởng giới thiệu gian hàng ID' . $ot->shop_id . ' ' . $ot->sale_before_shop . ' V trong đơn hàng OR' . $this->order_id . '. ';
                    }
                }
            }
            if ($addmoney) {
                foreach ($addmoney as $user_id => $it) {
                    $gca_coin = \common\models\gcacoin\Gcacoin::getModel($user_id);
                    $first_coin = $gca_coin->getCoinRed();
                    if ($gca_coin->addCoinRed($it['coin']) && $gca_coin->save(false)) {
                        $history = new \common\models\gcacoin\GcaCoinHistory();
                        $history->user_id = $user_id;
                        $history->type = 'PAY_MEMBER_ADD_AFFILIATE';
                        $history->data = $it['note'] . ' Tổng: ' . $it['coin'] . ' V.';
                        $history->gca_coin = $it['coin'];
                        $history->first_coin = $first_coin;
                        $history->last_coin = $gca_coin->getCoinRed();
                        $history->type_coin = 1;
                        $history->save(false);
                    } else {
                        $siteinfo = \common\components\ClaLid::getSiteinfo();
                        $email_manager = $siteinfo->email;
                        if ($email_manager) {
                            \common\models\mail\SendEmail::sendMail([
                                'email' => $email_manager,
                                'title' => 'Thêm tiền lỗi',
                                'content' => json_encode([$user_id => $it])
                            ]);
                        }
                    }
                }
            }
        }
    }

    public static function getAllAddMoneyOther()
    {
        $hour_confinement_config = \common\models\gcacoin\Config::getHourConfinement();
        $hour_confinement = time() - $hour_confinement_config;
        return self::find()->where(['status' => \common\models\order\Order::ORDER_DELIVERING, 'status_check_cancer' => \common\components\ClaLid::STATUS_DEACTIVED])->andWhere(" updated_at <= '$hour_confinement' ")->limit(15)->all();
    }

    public static function countOrder($order_status, $user_id, $options = [])
    {
        $condition = "ot.user_before_product=$user_id AND t.status IN (" . implode(',', $order_status) . ")";
        if (isset($options['start_date']) && $options['start_date']) {
            $condition .= ' AND t.created_at >= :start_date';
            $start_date_string = $options['start_date'] . ' 00:00:00';
            $start_date = strtotime($start_date_string);
            $params[':start_date'] = $start_date;
        }
        if (isset($options['end_date']) && $options['end_date']) {
            $condition .= ' AND t.created_at <= :end_date';
            $end_date_string = $options['end_date'] . ' 23:59:59';
            $end_date = strtotime($end_date_string);
            $params[':end_date'] = $end_date;
        }
        if (isset($options['affiliate_id']) && $options['affiliate_id']) {
            $condition .= ' AND t.affiliate_id=:affiliate_id';
            $params[':affiliate_id'] = $options['affiliate_id'];
        }
        $count = (new \yii\db\Query())->select('COUNT(*) as count')
            ->from('order_item AS ot')
            ->rightJoin('order_shop AS t', 't.id=ot.order_shop_id')
            ->groupBy('t.id')
            ->where($condition, $params)
            ->count();
        return $count;
    }

    public static function getAllOrder($user_id, $options = [])
    {
        $condition = 'c.object_type =1 AND t.user_before_product=:user_id';
        $params = [
            ':user_id' => $user_id
        ];
        if (isset($options['start_date']) && $options['start_date']) {
            $condition .= ' AND t.created_at >= :start_date';
            $start_date_string = $options['start_date'] . ' 00:00:00';
            $start_date = strtotime($start_date_string);
            $params[':start_date'] = $start_date;
        }
        if (isset($options['end_date']) && $options['end_date']) {
            $condition .= ' AND t.created_at <= :end_date';
            $end_date_string = $options['end_date'] . ' 23:59:59';
            $end_date = strtotime($end_date_string);
            $params[':end_date'] = $end_date;
        }
        $data = (new \yii\db\Query())
            ->select('t.*, c.operating_system, r.campaign_source, r.aff_type, r.campaign_name')
            ->from('order_item t')
            ->distinct()
            ->leftJoin('affiliate_click c', 'c.object_id = t.product_id')
            ->leftJoin('affiliate_link r', 'r.user_id = t.user_before_product')
            ->where($condition, $params)
            ->all();
        return $data;
    }

    function getFeeTransport()
    {
        if ($this->transport_type) {
            $free_rp = Transport::getInfoTransport($this);
            if ($free_rp && isset($free_rp['order']) && $free_rp['order']) {
                $this->shipfee = $free_rp['fee'];
                $this->transport_id = $free_rp['order'];
            } else {
                $this->transport_type = 0;
            }
            $this->save();
        }
    }
}
