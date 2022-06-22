<?php

/**
 * class for all payment object extend
 *
 * @author Admin
 */

namespace common\components\shipping;

//
abstract class ShippingMethod {

    public $cart = null;

    //
    abstract protected function loadOptions($options = []); // load options (configs) of gate
    abstract protected function calculateFee($options = []); // Tinh phi van chuyen
    abstract protected function createOrder($options = []); // Tao hóa đơn vận chuyển
    //
     /**
     * load cart
     * @param type $cart
     */

    function loadCart($cart = null) {
        $this->cart = $cart;
    }

    /**
     * 
     * @return type
     */
    function getCart() {
        return $this->cart;
    }

}
