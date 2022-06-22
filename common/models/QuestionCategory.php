<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "news_category".
 *
 * @property string $id
 * @property string $name
 * @property string $alias
 * @property string $parent
 * @property integer $level
 * @property string $track
 * @property string $created_at
 * @property string $updated_at
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $meta_title
 * @property integer $status
 * @property integer $avatar_path
 * @property integer $avatar_name
 * @property integer $avatar
 */
class QuestionCategory extends \yii\db\ActiveRecord {

    public $avatar = '';
    const ROOT_CATEGORY = 0;

    private $_cats = array('' => ' --- Chọn danh mục --- ');
    private $_dataProvider = array();

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'question_category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['parent', 'level', 'created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'alias','avatar_path','avatar_name', 'track', 'meta_keywords', 'meta_description', 'meta_title'], 'string', 'max' => 255],
            [['name', 'avatar', 'name_en', 'alias', 'track', 'meta_keywords', 'meta_description', 'meta_title','description','description_en','view_layout','avatar_path','avatar_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên danh mục',
            'name_en' => 'Tên danh mục (En)',
            'alias' => 'Alias',
            'parent' => 'Danh mục cha',
            'level' => 'Level',
            'track' => 'Track',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'meta_title' => 'Meta Title',
            'status' => 'Trạng thái',
            'description' => 'Mô tả',
            'description_en' => 'Mô tả (En)',
            'view_layout' => 'Hiển thị giao diện',
            'avatar' => 'Ảnh đại diện',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->name);
            return true;
        } else {
            return false;
        }
    }

    public static function optionsCategory() {
        $data = ['' => '--- chọn danh mục---'];
        $cats = QuestionCategory::find()->all();
        if($cats) {
            foreach ($cats as $cat) {
                $data[$cat['id']] = $cat['name'];
            }
        }
        return $data; 
    }

}
