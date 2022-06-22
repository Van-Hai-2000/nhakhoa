<?php

namespace common\models\notifications;

use common\components\ClaNotification;
use common\models\user\UserInGroup;
use frontend\models\User;
use Yii;
use yii\helpers\Url;
use common\components\ClaLid;
use yii\db\Query;

/**
 * This is the model class for table "notifications".
 *
 * @property string $id
 * @property integer $type_user
 * @property integer $user_id
 * @property integer $type
 * @property integer $type_id
 * @property string $title
 * @property string $description
 * @property string $link
 * @property integer $created_at
 * @property integer $updated_at
 */
class Notifications extends \common\models\ActiveRecordC
{

    const PROMOTION = 1; // Thông báo khuyến mãi
    const ORDER = 2; // Thông báo đơn hàng
    const UPDATE_SYSTEM = 3; // Cập nhật hệ thống
    const SYSTEM = 4; // Thông báo chung hệ thống
    const TYPE_USER_ALL = 4;

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_user', 'user_id', 'type', 'type_id', 'created_at', 'updated_at'], 'integer'],
            [['user_id', 'title', 'description','type'], 'required'],
            [['description'], 'string'],
            [['title', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_user' => 'Loại người nhận',
            'user_id' => 'Người nhận',
            'type' => 'Loại thông báo',
            'type_id' => 'Type ID',
            'title' => 'Tiêu đề',
            'description' => 'Nội dung',
            'link' => 'Đường dẫn',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    //Danh sách tất cả user
    public static function getAllUserSelect()
    {
        $response = [];
        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->asArray()->all();
        if($users){
            foreach ($users as $user){
                $response[$user['id']] = $user['id'].' - '.$user['username'];
            }
        }
        return $response;
    }

    //Danh sách loại thông báo
    public static function optionsType()
    {
        return [
            self::SYSTEM => 'Thông báo chung',
            self::PROMOTION => 'Khuyến mãi',
            self::UPDATE_SYSTEM => 'Cập nhật'
        ];
    }

    public static function getTypeName($type)
    {
        $options = self::optionsType();
        return isset($options[$type]) ? $options[$type] : '';
    }

    //Icon cho từng loại thông báo
    public static function getImageNotification($type)
    {
        $img = Url::home() . 'images/';
        if ($type == self::PROMOTION) {
            $img .= 'icon-giamgia.png';
        } else if ($type == self::ORDER) {
            $img .= 'icon-order.png';
        } else if ($type == self::UPDATE_SYSTEM) {
            $img .= 'icon-system.png';
        }
        return $img;
    }

    //Gửi thông báo lên app
    public static function sendNotification($options = []){
        $user_ids = [];
        $user_ids[] = $options['user_id'];
        if($options['user_id'] == -1){
            $user_ids = User::find()->select('id')->where(['status' => User::STATUS_ACTIVE])->asArray()->column();
        }
        ClaNotification::sendNotification(strip_tags($options['title']),  strip_tags($options['description']), $user_ids, ['title' => $options['title'],'description' => $options['description'],'link' => $options['link'], 'type' => $options['type'],'type_id' => $options['type_id']]);
        return true;
    }

}
