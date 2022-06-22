<?php

namespace common\models\log;

use backend\models\UserAdmin;
use Yii;

/**
 * This is the model class for table "activerecordlog".
 *
 * @property string $id
 * @property string $description
 * @property string $action
 * @property string $model
 * @property integer $idModel
 * @property string $record_before
 * @property string $record_after
 * @property string $user_id
 * @property integer $created_at
 */
class LogCustom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activerecordlog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idModel', 'created_at'], 'integer'],
            [['record_before', 'record_after'], 'string'],
            [['description'], 'string', 'max' => 500],
            [['action', 'user_id'], 'string', 'max' => 45],
            [['model'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Mô tả',
            'action' => 'Hành động',
            'model' => 'Nơi thay đổi',
            'idModel' => 'Id Model',
            'record_before' => 'Nội dung trước thay đổi',
            'record_after' => 'Nội dung sau thay đổi',
            'user_id' => 'Người thay đổi',
            'created_at' => 'Thời gian',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public function getUserAdmin()
    {
        return $this->hasOne(UserAdmin::className(), ['id' => 'user_id'])->select('id,username,fullname,vai_tro');
    }
}
