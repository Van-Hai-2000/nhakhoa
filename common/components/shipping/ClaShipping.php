<?php

/**
 * Class for load, process shipping
 *
 * @author minhbn
 */

namespace common\components\shipping;

//
class ClaShipping {

    const METHOD_GHTK = 1; // hình thức giao hàng tiếp kiệm
    const METHOD_GHN = 2; // hình thức giao hàng tiếp kiệm
    const SHIPPING_STATUS_WAITING = 1; // chưa thanh toán
    const SHIPPING_STATUS_SUCCESS = 2; // đã thanh toán
    const SHIPPING_STATUS_CANCEL = 0; // hủy đơn hàng khi thanh toán

    //
    public $cart = null;
    public $vendor = null;

    //
    function __construct($options = []) {
        if (isset($options['cart']) && $options['cart']) {
            $this->loadOrder($options['cart']);
        }
        //
        $this->loadVendor($options);
    }
    /**
     * Tinh phi van chuyen
     * 
     * @param type $options
     * @return boolean
     */
    function calculateFee($options=array()) {
        if ($this->getVendor() === NULL) {
            return false;
        }
        $this->vendor->loadCart($this->getCart());
        //
        return $this->vendor->calculateFee($options);
    }
    /**
     * Tao hoa đơn
     * 
     * @param type $options
     */
    function createOrder($options=array()){
        if ($this->getVendor() === NULL) {
            return false;
        }
        $this->vendor->loadCart($this->getCart());
        //
        return $this->vendor->createOrder($options);
    }

    function getInfoOrder($options=array()){
        if ($this->getVendor() === NULL) {
            return false;
        }
        $this->vendor->loadCart($this->getCart());
        //
        return $this->vendor->getInfoOrder($options);
    }

    function getInfoOrderUpdate($options=array()){
        if ($this->getVendor() === NULL) {
            return false;
        }
        $this->vendor->loadCart($this->getCart());
        //
        return $this->vendor->getInfoOrderUpdate($options);
    }

     function cancerOrder($options=array()){
        if ($this->getVendor() === NULL) {
            return false;
        }
        $this->vendor->loadCart($this->getCart());
        //
        return $this->vendor->cancerOrder($options);
    }

    function updateStatusFromHook($options=array()){
        if ($this->getVendor() === NULL) {
            return false;
        }
        $this->vendor->loadCart($this->getCart());
        //
        return $this->vendor->updateStatusFromHook($options);
    }
    
    
    
    
    /**
     * load cart
     * @param type $cart
     */
    function loadCart($cart = null) {
        $this->cart = $cart;
    }

    /**
     * load shipping method and there object
     * @param type $options
     */
    function loadVendor($options = []) {
        $cart = $this->getCart();
        $shippingMethod = isset($options['method']) ? $options['method'] : '';
        if (!$shippingMethod && $cart !== null) {
            $shippingMethod = $cart['shipping_method'];
        }
        if ($shippingMethod) {
            $this->vendor = $this->getVendor($shippingMethod);
        }
    }

    function loadOptions($options = []) {
        if ($this->vendor === NULL) {
            $message = \Yii::t('shipping_alert', 'shipping_is_null');
            $this->throwException($message);
        }
        //
        $this->vendor->loadOptions($options);
    }

    /**
     * Throw exception message
     * @param type $message
     * @throws Exception
     */
    function throwException($message = '') {
        throw new Exception($message);
    }

    function getShippingFromMethod($method = '') {
        $gateString = 'common\components\shipping\giaohangtietkiem\ClaGiaoHangTietKiem';
        if($method == self::METHOD_GHN) {
            $gateString = 'common\components\shipping\giaohangnhanh\ClaGiaoHangNhanh';
        }
        return new $gateString;
    }

    /**
     * get,set
     * @return type
     */
    function getCart() {
        return $this->cart;
    }

    function getVendor($method = '') {
        if ($method !== '') {
            $this->vendor = $this->getShippingFromMethod($method);
        }
        return $this->vendor;
    }

    function setCart($cart) {
        $this->cart = $cart;
    }

    function setVendor($vendor=null) {
        $this->vendor = $vendor;
    }

    public static function getCost($method, $options) {
        $claShipping = new \common\components\shipping\ClaShipping();
        $claShipping->loadVendor(['method' => $method]);
        $data = $claShipping->calculateFee($options);
        return isset($data['fee']) ? $data['fee'] : false;
    }

}
