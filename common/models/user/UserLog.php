<?php

namespace common\models\user;

use backend\models\UserAdmin;
use common\models\branch\Branch;
use Yii;

/**
 * This is the model class for table "user_log".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $admin_id
 * @property integer $branch_id
 * @property string $record_before
 * @property string $record_after
 * @property string $action
 * @property integer $created_at
 */
class UserLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'branch_id', 'created_at'], 'integer'],
            [['record_before', 'record_after'], 'string'],
            [['admin_id', 'action'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Tên bệnh nhân',
            'admin_id' => 'Người thực hiện',
            'branch_id' => 'Chi nhánh',
            'record_before' => 'Dữ liệu trước thay đổi',
            'record_after' => 'Dữ liệu sau thay đổi',
            'action' => 'Hành động',
            'created_at' => 'Thời gian',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = time();
            return true;
        } else {
            return false;
        }
    }

    public function getUserAdmin()
    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'admin_id'])->select('id,fullname');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->select('id,name');
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->select('id,username');
    }
}
