<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\components\ClaLid;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $type_social
 * @property string $id_social
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $facebook
 * @property string $link_facebook
 */
class User extends ActiveRecord
{

    //const STATUS_DELETED = 0;
    //const STATUS_ACTIVE = 1;
    const SPACE_REQUEST_TIME = 15000;

    /**
     * @inheritdoc
     */
    public $avatar1;
    public $avatar2;

    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['phone', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['status', 'default', 'value' => ClaLid::STATUS_ACTIVED],
            ['status', 'in', 'range' => [ClaLid::STATUS_ACTIVED, ClaLid::STATUS_DEACTIVED]],
            [['email', 'address', 'facebook', 'link_facebook', 'id_social'], 'string', 'max' => 255],
            [['type_social', 'avatar1', 'avatar2'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Họ và tên',
            'created_at' => 'Ngày tạo'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => ClaLid::STATUS_ACTIVED]);
    }

    public static function getAllUserId()
    {
        $data = User::find()->select('id')->asArray()->column();
        return $data;
    }

    public static function getAllUserIdShop()
    {
        $data = User::find()->rightJoin('shop', 'user.id = shop.id')->select('user.id')->where('user.id > 0')->asArray()->column();
        return $data;
    }

    public function isOnline()
    {
        if ($this->last_request_time + (self::SPACE_REQUEST_TIME / 1000) + 1 < time()) {
            return 0;
        }
        return 1;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->getfly_id) {
            $data = \common\components\api\ApiGetflycrm::addAccount($this);
            if (isset($data['code']) && $data['code']) {
                switch ($data['code']) {
                    case '201':
                        $this->getfly_id = $data['account_id'];
                        break;
                    case '402':
                        if (isset($data['accounts']) && isset($data['accounts'][0]['account_id'])) {
                            $this->getfly_id = $data['account_id'];
                        }
                        break;
                }
            }
            if ($this->getfly_id) {
                $this->save(false);
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }
}
