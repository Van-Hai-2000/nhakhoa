<?php

namespace common\models\media;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "video_category".
 *
 * @property string $id
 * @property string $name
 * @property string $alias
 * @property string $parent
 * @property integer $level
 * @property string $track
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $meta_title
 * @property integer $status
 * @property string $link
 * @property string $embed
 * @property integer $height
 * @property integer $width
 */
class VideoCategory extends \yii\db\ActiveRecord {

    const ROOT_CATEGORY = 0;

    private $_cats = array(self::ROOT_CATEGORY => ' --- Chọn danh mục --- ');
    private $_dataProvider = array();
    public $avatar = '';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'video_category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['parent', 'level', 'created_at', 'updated_at', 'status', 'height', 'width'], 'integer'],
            [['name', 'alias', 'track', 'avatar_path', 'avatar_name', 'meta_keywords', 'meta_description', 'meta_title', 'link', 'embed'], 'string', 'max' => 255],
            ['avatar', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên danh mục',
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
            'avatar' => 'Ảnh đại diện',
            'link' => 'Link video mặc định'
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

    /**
     * return options category
     * @param type $parent
     * @param type $level
     * @return type
     */
    public function optionsCategory($parent = 0, $level = 0) {
        $data = VideoCategory::find()->where(['parent' => $parent])->all();
        $glue = str_repeat('- - - ', $level);
        if ($data) {
            $level++;
            foreach ($data as $category) {
                $this->_cats[$category->id] = $glue . $category->name;
                $this->optionsCategory($category->id, $level);
            }
        }

        return $this->_cats;
    }

    public function getDataProvider($parent = 0, $level = 0) {
        $data = VideoCategory::find()->where(['parent' => $parent])->all();
        $glue = str_repeat('- - - ', $level);
        if ($data) {
            $level++;
            foreach ($data as $category) {
                $category->name = $glue . $category->name;
                $this->_dataProvider[$category->id] = $category->attributes;
                $this->getDataProvider($category->id, $level);
            }
        }

        return $this->_dataProvider;
    }

}
