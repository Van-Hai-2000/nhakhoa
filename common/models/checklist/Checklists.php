<?php

namespace common\models\checklist;

use common\models\ActiveRecordC;

/**
 * This is the model class for table "checklists".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Checklists extends ActiveRecordC
{
    const STATUS_TODO = 'Cần làm';
    const STATUS_DOING = 'Đang làm';
    const STATUS_CHECKING = 'Chờ kiểm tra';
    const STATUS_COMPLETE = 'Đã hoàn thành';

    public $attachmentsArray;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checklists';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 5000]
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
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày sửa',
        ];
    }

    /**
     *
     * get checklist status
     *
     * @return mixed
     */
    public static function getStatuses() {
        return self::getConstants('STATUS_', __CLASS__);
    }
}
