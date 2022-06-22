<?php

namespace common\models\qa;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "qa_category".
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
class QACategory extends \yii\db\ActiveRecord {

    public $avatar = '';
    const ROOT_CATEGORY = 0;
    //
    const NEWS_SALES = 27;

    private $_cats = array('' => ' --- Chọn danh mục --- ');
    private $_dataProvider = array();

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'qa_category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['parent', 'level', 'created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'alias','avatar_path','avatar_name', 'track', 'meta_keywords', 'meta_description', 'meta_title'], 'string', 'max' => 255],
            [['name', 'avatar', 'alias', 'track', 'meta_keywords', 'meta_description', 'meta_title','description','view_layout','avatar_path','avatar_name'], 'safe'],
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
            'description' => 'Mô tả',
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

    public static function getListCatById($id) {
        $list= [$id];
        $data = QACategory::find()->where(['parent' => $id])->all();
        if($data) {
            foreach ($data as $cat) {
                $list = array_merge($list, QACategory::getListCatById($cat['id']));
            }
        }
        return $list;
    }
    
    /**
     * return options category
     * @param type $parent
     * @param type $level
     * @return type
     */
    public function optionsCategory($parent = 0, $level = 0, $without_self = false) {
        $data = QACategory::find()->where(['parent' => $parent])->all();
        $glue = str_repeat('- - - ', $level);
        if ($data) {
            $level++;
            foreach ($data as $category) {
                $this->_cats[$category->id] = $glue . $category->name;
                $this->optionsCategory($category->id, $level);
            }
        }

        if($without_self && $this->id){
            unset($this->_cats[$this->id]);
        }
        return $this->_cats;
    }

    public function getDataProvider($parent = 0, $level = 0) {
        $data = QACategory::find()->where(['parent' => $parent])->all();
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
    
    public static function getDataById($parent = 0) {
        $result = (new \yii\db\Query())->select('*')
                ->from('qa_category')
                ->where('parent=:parent', [':parent' => $parent])
                ->orderBy('created_at DESC')
                ->all();
        $active = 0;
        for ($i=0; $i < count($result); $i++){
            $result[$i]['active'] = 0;
            if(isset($_GET['id']) && $result[$i]['id'] == $_GET['id']) {
                $result[$i]['active'] = 1;
                $active = 1;
            }
            // echo $_GET['id']; die();
            $result[$i]['items'] = QACategory::getDataById($result[$i]['id']);
            if(isset($result[$i]['items']['active']) && $result[$i]['items']['active']) {
                $result[$i]['active'] = 1;
                $active =1;
            }
        }
        $result['active'] = $active;
        return $result;
    }

}
