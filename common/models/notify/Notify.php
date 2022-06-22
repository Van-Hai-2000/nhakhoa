<?php

namespace common\models\notify;

use common\models\ActiveRecordC;
use Yii;

/**
 * This is the model class for table "notify".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $from
 * @property string $to
 * @property string $link
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Notify extends ActiveRecordC
{
    const NOTIFY_UNREAD = 0;
    const NOTIFY_READ = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notify';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['send_from', 'send_to', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tiêu đề',
            'description' => 'Mô tả',
            'from' => 'Gửi từ',
            'to' => 'Gửi đến',
            'link' => 'Liên kết',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày sửa',
        ];
    }
}
