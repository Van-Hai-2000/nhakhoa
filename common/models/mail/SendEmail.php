<?php

namespace common\models\mail;

use Yii;

/**
 * This is the model class for table "{{%send_email}}".
 *
 * @property string $id
 * @property string $email
 * @property string $title
 * @property string $content
 */
class SendEmail extends \common\components\ClaActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%send_email}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'title', 'content'], 'required'],
            [['content'], 'string'],
            [['email'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'title' => 'Title',
            'content' => 'Content',
        ];
    }

    public static function addMail($option) {
        if(isset($option['email']) && $option['email'] && isset($option['title']) && $option['title'] && isset($option['content']) && $option['content']) {
            $model = new self();
            $model->attributes = $option;
            $model->created_at = time();
            $model->status = 1;
            $model->count = 0;
            return $model->save(false);
        }
        return 0;
    }

    public static function sendMail($arr) {
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
            $content = "Cám ơn quý khách đã sử dụng dịch vụ của ocopmart.org.<br/> <a href='<?= __SERVER_NAME ?>/'>Đến trang web ngay</a>";
        }
        $content = \frontend\widgets\mail\MailWidget::widget([
            'view' => 'view',
            'input' => [
                'title' => $title,
                'content' => $content
            ]
        ]);
        \common\models\mail\SendEmail::addMail([
                'email' => $email,
                'title' => $title,
                'content' => $content,
            ]);
        // \Yii::$app->mailer->compose()
        //         ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name])
        //         ->setTo($email)
        //         ->setSubject($title)
        //         ->setHtmlBody($content)
        //         ->send();
    }
}
