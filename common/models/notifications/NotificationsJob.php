<?php

namespace common\models\notifications;

use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NotificationsJob extends \yii\base\BaseObject implements yii\queue\JobInterface {

    public $user_ids = [];
    public $notify = [];

    public function execute($queue) {
        $values = '';
        foreach ($this->user_ids as $user_id) {
            if ($values != '') {
                $values .= ',';
            }
            $values .= '("' . $this->notify['title'] . '", "' . $this->notify['description'] . '", "' . $this->notify['link'] . '", "' . $this->notify['type'] . '", "' . $user_id . '", 0, 0, ' . time() . ', ' . time() . ')';
        }
        $sql = 'INSERT INTO notifications (title, description, link, type, recipient_id, sender_id, created_at, updated_at) VALUES ' . $values;
        Yii::$app->db->createCommand($sql)->execute();
    }

}
