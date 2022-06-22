<?php

namespace common\models\recruitment;

use Yii;

/**
 * This is the model class for table "apply_work_history".
 *
 * @property string $id
 * @property string $apply_id
 * @property string $company
 * @property string $position
 * @property string $field_business
 * @property integer $scale
 * @property string $job_detail
 * @property string $time_work
 * @property string $reason_offwork
 */
class ApplyWorkHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'apply_work_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['apply_id', 'company', 'position', 'field_business', 'job_detail', 'time_work', 'reason_offwork'], 'required'],
            [['apply_id', 'scale'], 'integer'],
            [['company', 'position', 'field_business', 'time_work'], 'string', 'max' => 255],
            [['job_detail', 'reason_offwork'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'apply_id' => 'Apply ID',
            'company' => 'Company',
            'position' => 'Position',
            'field_business' => 'Field Business',
            'scale' => 'Scale',
            'job_detail' => 'Job Detail',
            'time_work' => 'Time Work',
            'reason_offwork' => 'Reason Offwork',
        ];
    }
    
    public static function getArrayScale() {
        return array(
            1 => '1 - 50 người',
            2 => '50 - 200 người',
            3 => '200 - 500 người',
            4 => '> 500 người',
        );
    }

}
