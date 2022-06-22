<?php

namespace common\models\recruitment;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "category".
 *
 * @property string $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 */
class Category extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
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
            'name' => 'Tên ngành nghề',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    /**
     * @hungtm
     * return array options category
     */
    public static function optionsCategory() {
        $data = (new Query())->select('*')
                ->from('category')
                ->all();
        return array_column($data, 'name', 'id');
    }

    /**
     * Hàm lấy ra 1 mảng key => value (id và tên category)
     * @param type $ids
     * @return type
     */
    public static function getNameCategory($ids) {
        $ids_array = explode(' ', $ids);
        $data = (new Query())->select('id, name')
                ->from('category')
                ->where('id IN (' . implode(',', $ids_array) . ')')
                ->all();
        return array_column($data, 'name', 'id');
    }

}
