<?php

namespace common\models\affiliate;

use Yii;

/**
 * This is the model class for table "affiliate_transfer_money".
 *
 * @property string $id
 * @property string $user_id
 * @property string $money số tiền yêu cầu chuyển
 * @property int $status 0: đang chờ; 1: đã chuyển; 2: không được chấp nhận
 * @property string $note ghi chú
 * @property string $note_admin ghi chú của admin
 * @property string $created_at
 * @property string $updated_at
 * @property string $image_path ảnh chuyển khoản
 * @property string $image_name
 */
class AffiliateTransferMoney extends \yii\db\ActiveRecord {

    const STATUS_WAITING = 0; // đang chờ
    const STATUS_TRANSFERED = 1; // đã chuyển
    const STATUS_FAILED = 2; // không được chấp nhận

    public $avatar = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'affiliate_transfer_money';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['user_id', 'money', 'status', 'created_at', 'updated_at'], 'integer'],
                [['note', 'note_admin'], 'string', 'max' => 1000],
                [['image_path', 'image_name'], 'string', 'max' => 255],
                [['avatar'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'money' => 'Số tiền',
            'status' => 'Status',
            'note' => 'Ghi chú',
            'note_admin' => 'Ghi chú của Admin',
            'created_at' => 'Thời gian tạo yêu cầu',
            'updated_at' => 'Thời gian cập nhật',
            'image_path' => 'Image Path',
            'image_name' => 'Image Name',
        ];
    }

    public function beforeSave($insert) {
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

    public static function arrStatus() {
        return [
            self::STATUS_WAITING => 'Đang chờ duyệt',
            self::STATUS_TRANSFERED => 'Đã chuyển tiền',
            self::STATUS_FAILED => 'Không được chấp nhận'
        ];
    }

    public static function getNameStatus($status) {
        $arrStatus = self::arrStatus();
        return $arrStatus[$status];
    }

    public static function getTotalMoneyKeep($status, $options = []) {
        $userId = Yii::$app->user->id;
        if(isset($options['user_id']) && $options['user_id']) {
            $userId = $options['user_id'];
        }
        $condition = 'user_id=:user_id AND status=:status';
        $params = [
            ':user_id' => $userId,
            ':status' => $status
        ];
        $total = (new \yii\db\Query())
                ->select('SUM(money)')
                ->from('affiliate_transfer_money')
                ->where($condition, $params)
                ->scalar();
        return $total;
    }

    public static function getAllDataByUserId($user_id) {
        $data = (new \yii\db\Query())
                ->select('*')
                ->from('affiliate_transfer_money')
                ->where('user_id=:user_id', [':user_id' => $user_id])
                ->orderBy('id DESC')
                ->all();
        return $data;
    }

}
