<?php

namespace common\models\news;

use Yii;

/**
 * This is the model class for table "content_page".
 *
 * @property string $id
 * @property string $title
 * @property string $alias
 * @property string $short_description
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $status
 */
class ContentPage extends \yii\db\ActiveRecord {
    
    public $avatar = '';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'content_page';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'description'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['title', 'alias', 'avatar_path', 'avatar_name', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 500],
            [['avatar'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Tiêu đề',
            'alias' => 'Alias',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Nội dung trang',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'status' => 'Trạng thái',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->title);
            return true;
        } else {
            return false;
        }
    }

     static function getDataSearch($option) {
        if($option['tag']) {
            $tag = $option['tag'];
        } else {
            $tag = null;
        }
        if(isset($option['limit'])) {
            $limit = $option['limit'];
        } else {
            $limit = \common\models\Search::LIMIT;
        }
        if(isset($option['page'])) {
            $page = $option['page'];
        } else {
            $page = 1;
        }
        $data = (new \yii\db\Query())->select('*')
                        ->from('content_page')
                        ->select('id, title, alias, meta_keywords, short_description as content')
                        ->where(['like','title',$tag])
                        ->orWhere(['like','meta_keywords',$tag])
                        ->orderBy('id DESC')
                        ->limit($limit)
                        ->offset(($page-1)*$limit)
                        ->all();
        for ($i=0; $i <count($data) ; $i++) { 
            $data[$i]['type'] = \common\models\Search::getTypeName(\common\models\Search::TYPE_PAGE);
            $data[$i]['link'] = \yii\helpers\Url::to(['/content-page/detail', 'id' =>$data[$i]['id'], 'alias' => $data[$i]['alias']]);
        }
        $tg['data'] = $data;
        $count = (new \yii\db\Query())->from('content_page')
                        ->where(['like','title',$tag])
                        ->orWhere(['like','meta_keywords',$tag])
                        ->count();
        $tg['pagination'] = new \yii\data\Pagination([
                    'defaultPageSize' => $limit,
                    'totalCount' => $count,
                ]);

        return $tg;
    }

}
