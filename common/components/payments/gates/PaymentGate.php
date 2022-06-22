<?php

/**
 * class for all payment object extend
 *
 * @author Admin
 */

namespace common\components\payments\gates;
//
abstract class PaymentGate {

    public $order = null;

    //
    abstract protected function loadOptions($options = []); // load options (configs) of gate
    abstract protected function process($options = []); // process transaction when payment
    abstract protected function paySuccess($options = []); // Call back when pay success
    abstract protected function cancel($options = []); // khi dung giao dich
    //
    function beforeProcess($options = []) {
        return true;
    }

    function afterProcess() {
        
    }
    /**
     * ham thanh toan
     * @param type $options
     */
    function pay($options = []) {
        if ($this->beforeProcess($options)) {
            $this->process();
            $this->afterProcess();
        }
    }

    /**
     * load order
     * @param type $order
     */
    function loadOrder($order = null) {
        $this->order = $order;
    }

    /**
     * 
     * @return type
     */
    function getOrder() {
        return $this->order;
    }

}
