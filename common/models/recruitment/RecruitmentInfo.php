<?php

namespace common\models\recruitment;

use Yii;

/**
 * This is the model class for table "recruitment_info".
 *
 * @property string $recruitment_id
 * @property string $benefit
 * @property string $description
 * @property string $job_requirement
 * @property string $record_consists
 */
class RecruitmentInfo extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'recruitment_info';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['recruitment_id'], 'required'],
            [['recruitment_id', 'benefit', 'description', 'job_requirement', 'record_consists'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'recruitment_id' => 'Recruitment ID',
            'benefit' => 'Quyền lợi được hưởng',
            'description' => 'Chi tiết công việc',
            'job_requirement' => 'Yêu cầu chuyên môn',
            'record_consists' => 'Hồ sơ bao gồm',
        ];
    }

}
