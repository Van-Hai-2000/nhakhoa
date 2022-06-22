<?php

/**
 * Interface for send message to mobiles
 *
 * @author Admin
 */

namespace common\components\notifications;

abstract class MobileNotify {

    //put your code here
    abstract protected function loadOptions($options = []); // load options (configs) of gate

    abstract protected function send($deviceToken, $data = []); // send message to device
}
