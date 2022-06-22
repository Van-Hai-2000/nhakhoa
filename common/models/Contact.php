<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */

class Contact extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'contact';
    }


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // name, email, address and body are required
            [['name', 'email', 'phone', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],

            [['phone', 'address', 'body', 'type', 'order', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => Yii::t('app','full_name'),
            'email' => 'Email',
            'phone' => Yii::t('app','phone'),
            'type' => Yii::t('app','types'),
            'address' => Yii::t('app','address'),
            'subject' => Yii::t('app','subject'),
            'order' => Yii::t('app','orders'),
            'body' => Yii::t('app','content'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email) {
        // return Yii::$app->mailer->compose()
        //                 ->setTo($email)
        //                 ->setFrom([$this->email => $this->name])
        //                 ->setSubject('Liên hệ')
        //                 ->setTextBody($this->body)
        //                 ->send();
        return 1;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if($this->isNewRecord) {
                \common\models\NotificationAdmin::addNotifaction('mail_contact');
            }
            return true;
        } else {
            return false;
        }
    }

}
