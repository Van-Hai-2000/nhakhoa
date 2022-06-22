<?php

namespace common\models\mail;

use Yii;

/**
 * This is the model class for table "{{%mail_config}}".
 *
 * @property string $id
 * @property string $email
 * @property string $host
 * @property string $password
 * @property integer $port
 * @property string $encryption
 */
class MailConfig extends \common\components\ClaActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'host', 'password', 'port', 'encryption'], 'required'],
            [['port'], 'integer'],
            [['email', 'host'], 'string', 'max' => 250],
            [['password'], 'string', 'max' => 50],
            [['encryption'], 'string', 'max' => 10],
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
            'host' => 'Host',
            'password' => 'Password',
            'port' => 'Port',
            'encryption' => 'Encryption',
        ];
    }
}
