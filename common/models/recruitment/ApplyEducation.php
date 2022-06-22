<?php

namespace common\models\recruitment;

use Yii;

/**
 * This is the model class for table "apply_education".
 *
 * @property string $id
 * @property string $apply_id
 * @property string $school
 * @property string $major
 * @property string $qualification_type
 */
class ApplyEducation extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'apply_education';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['apply_id', 'school', 'major', 'qualification_type'], 'required'],
            [['apply_id'], 'integer'],
            [['school', 'major', 'qualification_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'apply_id' => 'Apply ID',
            'school' => 'School',
            'major' => 'Major',
            'qualification_type' => 'Qualification Type',
        ];
    }

}
