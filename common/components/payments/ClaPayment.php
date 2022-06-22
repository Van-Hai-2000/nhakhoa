<?php

/**
 * Class for load, process payment
 *
 * @author minhbn
 */

namespace common\components\payments;

//
class ClaPayment
{

    const PAYMENT_STATUS_WAITING = 1; // chưa thanh toán
    const PAYMENT_STATUS_SUCCESS = 2; // đã thanh toán
    const PAYMENT_STATUS_CANCEL = 0; // hủy đơn hàng khi thanh toán
    const TRANFERED_MONEY = 1; // đã chuyển tiền cho user
    const NOT_TRANFERED_MONEY = 0; // chưa chuyển tiền cho user
    const PAYMENT_METHOD_NL = 'NL';
    const PAYMENT_METHOD_NR = 2;
    const PAYMENT_METHOD_QR = 'QR';
    const PAYMENT_METHOD_MEMBER = 'MEMBER'; // Phương thức thanh toán
    const PAYMENT_METHOD_MEMBERIN = 'MEMBERIN'; // Phương thức thanh toán
    const PAYMENT_METHOD_MEMBERVS = 'MEMBERVS'; // Phương thức thanh toán
    const PAYMENT_METHOD_VNPay = 'VNPAY'; // VNPay
    const PAYMENT_METHOD_CK = 'CK';
    const PAYMENT_METHOD_CKQR = 'CKQR';
    const TYPE_PAYMENT = 1; // Nạp tiền tài khoản
    //
    public $order = null;
    protected $payment = null;

    public static function getName($id)
    {
        $arr = self::optionOrderPayment();
        return isset($arr[$id]) ? $arr[$id] : \Yii::t('app', 'other_method');
    }

    public static function optionOrderPayment()
    {
        $arr = [
            self::PAYMENT_METHOD_NR => \Yii::t('app', 'cash_method'),
            self::PAYMENT_METHOD_QR => \Yii::t('app', 'qr_method'),
            // self::PAYMENT_METHOD_MEMBER => Yii::t('app', 'member_method'),
            self::PAYMENT_METHOD_MEMBERIN => \Yii::t('app', 'member_method'),
            self::PAYMENT_METHOD_VNPay => \Yii::t('app', 'vnp_method'),
            self::PAYMENT_METHOD_CK =>  \Yii::t('app', 'payment_ck'),
            self::PAYMENT_METHOD_CKQR =>  \Yii::t('app', 'payment_ckqr'),
            self::PAYMENT_METHOD_MEMBERVS =>  'Thanh toán bằng ' . __VOUCHER_SALE,
        ];
        return $arr;
    }

    //
    function __construct($options = [])
    {
        if (isset($options['order']) && $options['order']) {
            $this->loadOrder($options['order']);
        }
        //
        $this->loadPayment($options);
    }

    function pay()
    {
        $order = $this->order;
        switch ($order->payment_method) {
            case self::PAYMENT_METHOD_MEMBERIN:
                $orders = \common\models\order\Order::getOrderByKey($order->key);
                if ($orders) {
                    foreach ($orders as $order) {
                        if ($order->paymentV()) {
                            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
                            $order->save(false);
                        } else {
                            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_WAITING;
                            $order->save(false);
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            case self::PAYMENT_METHOD_MEMBERVS:
                $orders = \common\models\order\Order::getOrderByKey($order->key);
                if ($orders) {
                    foreach ($orders as $order) {
                        if ($order->paymentV()) {
                            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_SUCCESS;
                            $order->save(false);
                        } else {
                            $order->payment_status = \common\components\payments\ClaPayment::PAYMENT_STATUS_WAITING;
                            $order->save(false);
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            case self::PAYMENT_METHOD_CK:
                return false;
        }
        if ($this->getPayment() === NULL) {
            return false;
        }
        $this->payment->loadOrder($this->getOrder());
        return $this->payment->pay();
    }
    /**
     * cancel transction
     * @return boolean
     */
    function cancel()
    {
        if ($this->getPayment() === NULL) {
            return false;
        }
        $this->payment->loadOrder($this->getOrder());
        //
        return $this->payment->cancel();
    }


    /**
     * 
     * @return boolean
     */
    function paySuccess()
    {
        if ($this->getPayment() === NULL) {
            return false;
        }
        $this->payment->loadOrder($this->getOrder());
        //
        return $this->payment->paySuccess();
    }

    /**
     * load order
     * @param type $order
     */
    function loadOrder($order = null)
    {
        $this->order = $order;
    }

    /**
     * load payment method and there object
     * @param type $options
     */
    function loadPayment($options = [])
    {
        $order = $this->getOrder();
        $paymentMethod = isset($options['method']) ? $options['method'] : '';
        if (!$paymentMethod && $order !== null) {
            $paymentMethod = $order['payment_method'];
        }
        if ($paymentMethod) {
            $this->payment = $this->getPayment($paymentMethod);
        }
    }

    function loadOptions($options = [])
    {
        if ($this->payment === NULL) {
            $message = \Yii::t('payment_alert', 'payment_is_null');
            $this->throwException($message);
        }
        //
        $this->payment->loadOptions($options);
    }

    /**
     * Throw exception message
     * @param type $message
     * @throws Exception
     */
    function throwException($message = '')
    {
        throw new Exception($message);
    }

    function getPaymentFromMethod($method = '')
    {
        $gateString = 'common\components\payments\gates\nganluong\NganLuongGate';
        switch ($method) {
            case self::PAYMENT_METHOD_MEMBER: {
                    $gateString = 'common\components\payments\gates\member\MemberGate';
                }
                break;
            case self::PAYMENT_METHOD_VNPay: {
                    $gateString = 'common\components\payments\gates\vnpay\VNPayGate';
                }
                break;
        }
        return new $gateString;
    }

    /**
     * get,set
     * @return type
     */
    function getOrder()
    {
        return $this->order;
    }

    function getPayment($method = '')
    {
        if ($method !== '') {
            $this->payment = $this->getPaymentFromMethod($method);
        }
        return $this->payment;
    }

    function setOrder($order)
    {
        $this->order = $order;
    }

    function setPayment($payment)
    {
        $this->payment = $payment;
    }
}
