<?php

namespace common\models\qa;

use Yii;
use common\components\ClaLid;
use yii\db\Query;

/**
 * This is the model class for table "qa".
 *
 * @property string $id
 * @property string $title
 * @property string $alias
 * @property string $category_id
 * @property string $short_description
 * @property string $description
 * @property integer $status
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $meta_title
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $author
 * @property string $updated_at
 * @property string $publicdate
 * @property string $viewed
 * @property string $source
 * @property integer $ishot
 * @property string $created_at
 */
class QA extends \yii\db\ActiveRecord
{

    const DEFAULT_ORDER = " id DESC ";
    const CATEGORY_GUIGE = 1;
    public $avatar = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'category_id', 'description'], 'required'],
            [['category_id', 'status', 'updated_at', 'publicdate', 'viewed', 'ishot', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['title', 'alias', 'meta_keywords', 'meta_description', 'meta_title', 'avatar_path', 'avatar_name', 'author', 'source'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 500],
            [['avatar'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Tiêu đề',
            'alias' => 'Alias',
            'category_id' => 'Danh mục',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Chi tiết câu hỏi',
            'status' => 'Trạng thái',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'meta_title' => 'Meta Title',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'author' => 'Tác giả',
            'updated_at' => 'Updated At',
            'publicdate' => 'Publicdate',
            'viewed' => 'Viewed',
            'source' => 'Nguồn câu hỏi',
            'ishot' => 'Câu hỏi nổi bật',
            'created_at' => 'Created At',
        ];
    }

    public function beforeSave($insert)
    {
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

    /**
     *
     * @param array $options
     * @return int|array
     */
    public static function getqa($options = [], $countOnly = false)
    {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        //
        if (isset($options['ishot']) && $options['ishot']) {
            $condition .= ' AND ishot=:ishot';
            $params[':ishot'] =  $options['ishot'];
        }
        //
        if (isset($options['category_id']) && $options['category_id']) {
            if(is_array($options['category_id'])) {
                $condition .= " AND category_id IN ('".implode(" ',' " , $options['category_id'])."')";
            } else {
                $condition .= ' AND category_id=:category_id';
                $params[':category_id'] = $options['category_id'];
            }
            
        }
        //
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        if (!isset($options['page'])) {
            $options['page'] = 1;
        }
        $offset = ($options['page'] - 1) * $options['limit'];
        //
        if (isset($countOnly) && $countOnly) {
            return QA::find()
                ->where($condition, $params)
                ->count();
        }
        return QA::find()
            ->where($condition, $params)
            ->orderBy('publicdate DESC')
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
    }

    public static function getNewByAttr($options = [], $countOnly = false)
    {
        $where ="status = 1";
        if (isset($options['attr']) && $options['attr']) {
            foreach ($options['attr'] as $key => $value) {
                $where .= " AND $key = '$value' ";
            }
        }
        if (isset($options['_new']) && $options['_new']) {
           $where .= " AND id <> ".$options['_new'];
        }

        $order = QA::DEFAULT_ORDER;
        if (isset($options['order']) && $options['order']) {
            $order = $options['order'];
        }

        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        //
        $qa = (new Query())->select('*')
                ->from('qa')
                ->where($where)
                ->orderBy($order)
                ->limit($limit)
                ->all();
        return $qa;
    }


    public static function countAllqa()
    {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED,
        ];
        return QA::find()
            ->where($condition, $params)
            ->count();
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
        $data = (new \yii\db\Query())
                        ->from('qa')
                        ->select('id, alias, title, meta_keywords, short_description as content, avatar_name, avatar_path')
                        ->where(['like','title',$tag])
                        ->orWhere(['like','meta_keywords',$tag])
                        ->orderBy('id DESC')
                        ->limit($limit)
                        ->offset(($page-1)*$limit)
                        ->all();
        for ($i=0; $i <count($data) ; $i++) { 
            $data[$i]['type'] = \common\models\Search::getTypeName(\common\models\Search::TYPE_NEW);
            $data[$i]['link'] = \yii\helpers\Url::to(['/qa/qa/detail', 'id' =>$data[$i]['id'], 'alias' => $data[$i]['alias']]);
        }
        $tg['data'] = $data;
        $count = (new \yii\db\Query())
                        ->from('qa')
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
