<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/21/2018
 * Time: 5:50 PM
 */

namespace common\components;

use common\models\cron\CronEmail;
use common\models\gcacoin\GcaCoinHistory;
use common\models\gcacoin\PhoneOtp;
use common\models\notifications\Notifications;
use common\models\order\Order;
use common\models\product\Product;
use common\models\qrcode\PayQrcode;
use common\models\User;
use Da\QrCode\QrCode;
use Yii;

class ClaQrCode
{
    const URL_OTP = __SERVER_NAME . '/api/v1/sms/';
    const TOKEN_OTP = 'ios16052018_gca2018';
    const TRANSACTION_CODE = 'GCA';
    const TYPE_USER = 'user';
    const TYPE_PRODUCT = 'product';
    const TYPE_ORDER = 'order';
    const TYPE_WITHDRAW_SUCCESS = 'withdraw_success';
    const TYPE_WITHDRAW_CANCEL = 'withdraw_cancel';
    const TYPE_MESSAGE_OFFLINE = 'message_offline';

    public static function GenQrCode($options = [])
    {
        $type = isset($options['type']) ? $options['type'] : '';
        $price = isset($options['price']) ? $options['price'] : 0;
        $data = isset($options['data']) ? $options['data'] : array();
        if (!$type) {
            return false;
        }
        switch ($type) {
            case 'order':
                if (!isset($data['order_id']) || !$price) {
                    return false;
                }
                break;
            case 'product':
                if (!isset($data['id']) || !$price) {
                    return false;
                }
                break;
            case 'user':
                if (!isset($data['user_id'])) {
                    return false;
                }
                break;
        }

        if (!empty($data)) {
            if (isset($data['order_id']) && $data['order_id']) {
                $token = md5($type . $price . $data['order_id']);
            } else if (isset($data['id']) && $data['id']) {
                $token = md5($type . $price . $data['id'] . json_encode($data));
            } else if (isset($data['user_id']) && $data['user_id']) {
                $token = md5($type . $data['user_id']);
            }
        }
        $src = '#';
        if (isset($token)) {
            $file_name = $token . '.png';
            $qrCode = (new QrCode($token))
                ->setSize(150)
                ->setMargin(5)
                ->useForegroundColor(51, 153, 255);
            $qrCode->writeFile($file_name); // writer defaults to PNG when none is specified
            $src = $qrCode->getLinkFile($file_name);
            $qr = \common\models\qrcode\PayQrcode::findOne(['token' => $token]);
            if (!$qr) {
                $qr = new PayQrcode();
                $qr->token = $token;
                $qr->type = $type;
                $qr->price = $price;
                if (isset($data) && !isset($data['user_id']) && isset($data['shipfee'])) {
                    $data['shipfee'] = $data['shipfee'] ? $data['shipfee'] : 0;
                }
                $qr->data = json_encode($data);
                $qr->save();
            }
        }
        return $src;
    }


    public static function CheckPayment($options = [])
    {
        switch ($options['type']) {
            case self::TYPE_ORDER:
                return \common\components\ClaOrderQr::UpdateOrder($options);
                break;
            case self::TYPE_PRODUCT:
                return \common\components\ClaOrderQr::AddOrder($options);
                break;
        }
    }

    //send mail to cronEmail
    public static function SendMail($email = '', $coin = 0, $last_coin = 0)
    {
        $cron = new CronEmail();
        $cron->email = $email;
        $cron->content = 'Số dư thay đổi <b style="color: green">-' . $coin . '</b>gcacoin.  Số dư hiện tại: <b style="color: green">' . ClaGenerate::decrypt($last_coin) . '</b>';
        $cron->save();
    }

    //send to email
    public static function SendToEmail($email = '', $body = '')
    {
    }

    //send notification khi thanh toán thành công
    public static function SendNotifi($type = '', $data = array(), $coin = 0, $last_coin = 0, $user_id = 0)
    {
        $noti = new Notifications();
        $info_noti = json_decode($data);
        if ($type == self::TYPE_PRODUCT) {
            $name = 'Sản phẩm: <b style="color: green">' . Product::findOne($info_noti->id)->name . '</b>';
            $quantity = 'Số lượng <b style="color: green">' . $info_noti->quantity . '</b>';
            $ship = 'Phí ship <b style="color: green">' . number_format($info_noti->shipfee) . '</b> đ';
            $noti->title = 'Thanh toán sản phẩm thành công.';
            $noti->description = 'Thanh toán sản phẩm thành công. ' . $name . '. ' . $quantity . '. ' . $ship . '. Số dư thay đổi <b style="color: green">-' . $coin . '</b> gcacoin.  Số dư hiện tại: <b style="color: green">' . ClaGenerate::decrypt($last_coin) . '</b> gcacoin';
            $noti->link = '#';
            $noti->type = 3;
            $noti->recipient_id = $user_id;
            $noti->unread = ClaLid::STATUS_ACTIVED;
            $noti->save();
        } elseif ($type == self::TYPE_ORDER) {
            $key = 'Key: <b style="color: green">' . Order::findOne($info_noti->order_id)->key;
            $code = 'Mã hóa đơn <b style="color: green"> OR' . $info_noti->order_id . '</b>';
            $noti->title = 'Thanh toán sản phẩm thành công.';
            $noti->description = 'Thanh toán đơn hàng thành công. ' . $key . '. ' . $code . '. Số dư thay đổi <b style="color: green">-' . $coin . '</b> gcacoin.  Số dư hiện tại: <b style="color: green">' . ClaGenerate::decrypt($last_coin) . '</b> gcacoin';
            $noti->link = '#';
            $noti->type = 3;
            $noti->recipient_id = $user_id;
            $noti->unread = ClaLid::STATUS_ACTIVED;
            $noti->save();
        } elseif ($type == self::TYPE_USER) {
            $noti->title = $info_noti->title;
            $noti->description = $info_noti->content;
            $noti->link = '<?= __SERVER_NAME ?>/vi-ocop-v.html';
            $noti->type = 3;
            $noti->recipient_id = $user_id;
            $noti->unread = ClaLid::STATUS_ACTIVED;
            $noti->save();
        } elseif ($type == self::TYPE_WITHDRAW_SUCCESS) {
            $noti->title = 'Yêu cầu rút tiền thành công.';
            $noti->description = 'Bạn đã rút <b style="color: green">' . $coin . __VOUCHER_RED . '</b> thành công. Số dư thay đổi <b style="color: green">-' . $coin . __VOUCHER_RED . '</b>.  Số dư hiện tại: <b style="color: green">' . $last_coin . __VOUCHER_RED . '</b>';
            $noti->link = '#';
            $noti->type = 3;
            $noti->recipient_id = $user_id;
            $noti->unread = ClaLid::STATUS_ACTIVED;
            $noti->save();
        } elseif ($type == self::TYPE_WITHDRAW_CANCEL) {
            $data = json_decode($data);
            $noti->title = 'Hủy yêu cầu rút tiền.';
            $noti->description = 'Yêu cầu rút ' . $coin . __VOUCHER_RED . ' của bạn đã bị hủy. Nguyên nhân: ' . $data->body;
            $noti->link = '#';
            $noti->type = 3;
            $noti->recipient_id = $user_id;
            $noti->unread = ClaLid::STATUS_ACTIVED;
            $noti->save();
        } elseif ($type == self::TYPE_MESSAGE_OFFLINE) {
            $noti->title = 'Bạn có tin nhắn mới.';
            $noti->description = 'Bạn có tin nhắn mới từ: ' . User::findOne($info_noti->fromid)->username;
            $noti->link = '#';
            $noti->type = 3;
            $noti->recipient_id = $user_id;
            $noti->unread = ClaLid::STATUS_ACTIVED;
            $noti->save();
        }
    }

    //Lưu lịch sử thanh toán
    public static function PayHistory($user_id = 0, $to_id = 0, $type = '', $data = '', $gca_coin = 0, $first_coin = 0, $last_coin = 0)
    {
        $info_noti = json_decode($data);
        $history = new GcaCoinHistory();
        $history->user_id = $user_id;
        $history->to_id = $to_id;
        $history->type = $type;
        if ($type != self::TYPE_USER) {
            $history->data = $data;
        } else {
            $history->data = $info_noti->content;
        }
        $history->gca_coin = ($gca_coin);
        $history->first_coin = $first_coin;
        $history->last_coin = (float)ClaGenerate::decrypt($last_coin);
        if ($history->save()) {
            $return = [
                'success' => true,
                'data' => $history->attributes
            ];
        } else {
            $return = [
                'success' => false,
                'error' => $history->getErrors()
            ];
        }
        return json_encode($return);
    }

    //Lấy mã otp
    public static function getOtp($phone)
    {
        if (isset($phone) && $phone) {
            $params = [
                'phone' => $phone
            ];
            $url = self::URL_OTP . 'get-otp?token=' . self::TOKEN_OTP . '&phone=' . $phone;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $response = curl_exec($ch);
            curl_close($ch);
            $otp = (json_decode($response));
            if (isset($otp) && $otp) {
                if ($otp->success) {
                    $return = [
                        'success' => true,
                        'data' => $otp,
                    ];
                } else {
                    $return = [
                        'success' => false,
                        'message' => $otp->message,
                    ];
                }
            } else {
                $return = [
                    'success' => false,
                    'message' => 'Không kết nối được đến server OTP',
                ];
            }
        } else {
            $return = [
                'success' => false,
                'message' => 'Không tồn tại số điện thoại gửi OTP.'
            ];
        }
        return json_encode($return);
    }

    //Kiểm tra mã Otp
    public static function checkOtp($phone, $otp)
    {
        if ($phone && $otp) {
            $params = [
                'phone' => $phone,
                'otp' => $otp
            ];
            $url = self::URL_OTP . 'check-otp?token=' . self::TOKEN_OTP . '&phone=' . $phone . '&otp=' . $otp;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            if (isset($response) && $response) {
                if ($response->success) {
                    $return = [
                        'success' => true,
                        'message' => $response->message,
                    ];
                } else {
                    $return = [
                        'success' => false,
                        'message' => $response->message,
                    ];
                }
            } else {
                $return = [
                    'success' => false,
                    'message' => 'Không kết nối được đến server OTP',
                ];
            }
        } else {
            $return = [
                'success' => false,
                'message' => 'Không tồn tại số điện thoại gửi OTP.'
            ];
        }
        return json_encode($return);
    }

    public static function getOtpCheckAll($phone)
    {
        $check = json_decode(self::getOtp($phone), true);
        return $check;
    }

    public static function checkOtpCheckAll($phone, $otp)
    {
        $check = json_decode(self::checkOtp($phone, $otp), true);
        return $check;
    }

    //update mã OTP
    public static function updateOtp($phone, $otp)
    {
        if ($phone && $otp) {
            $params = [
                'phone' => $phone,
                'otp' => $otp
            ];
            $url = self::URL_OTP . 'update-otp?token=' . self::TOKEN_OTP . '&phone=' . $phone . '&otp=' . $otp;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            if (isset($response) && $response) {
                if ($response->success) {
                    $return = [
                        'success' => true,
                        'data' => $response,
                    ];
                } else {
                    $return = [
                        'success' => false,
                        'message' => $response->message,
                    ];
                }
            } else {
                $return = [
                    'success' => false,
                    'message' => 'Không kết nối được đến server OTP',
                ];
            }
        } else {
            $return = [
                'success' => false,
                'message' => 'Không tồn tại số điện thoại gửi OTP.'
            ];
        }
        return json_encode($return);
    }

    //Get sesstion
    public static function getSession($name)
    {
        $session = Yii::$app->session;
        $session->open();
        $value = $session->get($name);
        $session->close();
        if (isset($value) && $value) {
            return $value;
        } else {
            return false;
        }
    }

    //Set session
    public static function setSession($name, $value)
    {
        $session = Yii::$app->session;
        $session->open();
        $session[$name] = [
            'time' => time(),
            'status' => $value,
        ];
        $session->close();
        return true;
    }

    //Kết nối API check tài khoản bên member
    public static function ConnectXu($data = [])
    {
        $url = 'https://member.gcaeco.vn/checkout/modify';

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);
        return $result;
    }

    //Kết nối API quy đổi xu
    public static function GetXu($data)
    {
        $url = 'https://member.gcaeco.vn/checkout/checkoutcoin';

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function CountNotifiChat()
    {
        $url = 'https://member.gcaeco.vn/chats/count-notification-chat';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $dt = curl_exec($ch);
        curl_close($ch);
        return $dt;
    }
    public static function UpdateNotifiChat()
    {
        $url = 'https://member.gcaeco.vn/chats/update-admin-view';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $dt = curl_exec($ch);
        curl_close($ch);
        return $dt;
    }

    public static function UpdateReadChat($to_id, $from_id)
    {
        $url = 'https://member.gcaeco.vn/chats/update-admin-read';
        $param = array(
            'to_id' => $to_id,
            'from_id' => $from_id
        );

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, count($param));

        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);

        $data = curl_exec($ch);
        return $data;
    }
}
