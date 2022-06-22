<?php

/**
 * Description of ClaMobileNotify
 *
 * @author minhbn
 */

namespace common\components\notifications;

class ClaMobileNotify {

    protected $sender = null;

    function __construct($options = []) {
        $this->loadSender($options);
    }

    /**
     * Send message to user's devices
     * @param type $user_id
     * @param type $data
     * @return boolean
     */
    function send($user_id = 0, $data = []) {
        if ($this->getSender() === NULL) {
            return false;
        }
        if (!$user_id || !$data) {
            return false;
        }
        //
        $devices = \common\models\user\UserDevice::getUserDevices(array(
                    'user_id' => $user_id,
                    'getDevices' => true,
                        ));
        //
        return $this->sender->send($devices, $data);
    }

    /**
     * load sender method and there object
     * @param type $options
     */
    function loadSender($options = []) {
        $senderMethod = isset($options['method']) ? $options['method'] : 'firebase';
        if ($senderMethod) {
            $this->sender = $this->getSender($senderMethod);
        }
    }

    function getSender($method = '') {
        if ($method !== '') {
            $this->sender = $this->getSenderFromMethod($method);
        }
        return $this->sender;
    }

    function getSenderFromMethod($method = '') {
        $gateString = 'common\components\notifications\firebase\Notify';
        return new $gateString;
    }

}
