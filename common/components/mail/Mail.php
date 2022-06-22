<?php

/**
 * class for all payment object extend
 *
 * @author Admin
 */

namespace common\components\mail;

use Yii;
//
class Mail extends \yii\widgets\ActiveForm
{

    public function sendMail($arr)
    {
        $email = $arr["email"];
        // $url = $arr["url"];
        if (isset($arr["title"])) {
            $title = $arr["title"];
        } else {
            $title = "Thông báo từ ocopmart.org";
        }
        if (isset($arr["content"])) {
            $content = $arr["content"];
        } else {
            $content = "Cám ơn quý khách đã sử dụng dịch vụ của ocopmart.org.<br/> <a href='" . __SERVER_NAME . "'>Đến trang web ngay</a>";
        }
        \common\models\mail\SendEmail::sendMail([
            'email' => $email,
            'title' => $title,
            'content' => $content
        ]);
        // \Yii::$app->mailer->compose()
        //         ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
        //         ->setTo($email)
        //         ->setSubject($tieude)
        //         ->setHtmlBody($content)
        //         ->send();
    }
}
