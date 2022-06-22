<?php

namespace common\components;

use Yii;
use yii\db\Query;

/*
 * Class for create and show menu
 */

class ClaNotification
{
    const KEY_NOTIFICATION = 'AAAAsogrSA0:APA91bG6RShvQvGO3FRfLchFcSpfidLBDwX1s64iTrcLb8_pBZ-VR1pEErpQVSOvu6pqcUeouhDp9w9yqivRPSaeGAnchqTzAc-lueALyhcAUEGkgbDsKGw7ZsrYpWqrufJMDW-deBdl';

    static function sendNotification($title, $message, $user_id = null, $options = [])
    {
        $query = (new Query())->select('device_id')->from('user_device');
        if (!$user_id) {
            return false;
        }
        $data = $query->where(['user_id' => $user_id])->all();
        if ($data) {
            $registrationIds = array_column($data, 'device_id');
            $serverToken = self::KEY_NOTIFICATION;
            $msg = array(
                'title' => $title,
                'body' => $message,
            );
            $options['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
            if (count($registrationIds) < 1000) {
                $fields = array(
                    "registration_ids" => $registrationIds,
                    'notification' => $msg,
                    'data' => $options
                );
                $headers = array(
                    'Authorization: key=' . $serverToken,
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                curl_close($ch);
                return $result;
            } else {
                $registrationIds = array_chunk($registrationIds, 999);
                foreach ($registrationIds as $value) {
                    $fields = array(
                        "registration_ids" => $value,
                        'notification' => $msg,
                        'data' => $options
                    );
                    $headers = array(
                        'Authorization: key=' . $serverToken,
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    $result = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
        return false;
    }
}
