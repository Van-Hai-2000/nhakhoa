<?php

namespace common\models\recruitment;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "skill".
 *
 * @property string $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 */
class Skill extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'skill';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên kỹ năng',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    public static function getSkillsJsondata() {
        $data = (new Query())->select('*')
                ->from('skill')
                ->all();
        return json_encode(array_column($data, 'name', 'id'));
    }

    /**
     * @hungtm
     * return array options kill
     */
    public static function optionsSkill() {
        $data = (new Query())->select('*')
                ->from('skill')
                ->all();
        return array_column($data, 'name', 'id');
    }

    public static function getSkillByIds($ids_string) {
        $ids_array = explode(' ', $ids_string);
        $data = (new Query())->select('*')
                ->from('skill')
                ->where('id IN (' . implode(',', $ids_array) . ')')
                ->all();
        return array_column($data, 'name', 'id');
    }

}
