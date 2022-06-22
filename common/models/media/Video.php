<?php

namespace common\models\media;

use Yii;
use common\models\Province;
use common\models\media\VideoCategory;
use common\components\ClaLid;
use yii\db\Query;

/**
 * This is the model class for table "video".
 *
 * @property string $id
 * @property string $name
 * @property string $name_en
 * @property string $alias
 * @property integer $category_id
 * @property string $province_id
 * @property string $short_description
 * @property string $description
 * @property string $link
 * @property string $length
 * @property string $embed
 * @property integer $height
 * @property integer $width
 * @property integer $status
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property string $updated_at
 * @property string $viewed
 * @property integer $ishot
 * @property integer $homeslide
 */
class Video extends \yii\db\ActiveRecord {

    public $avatar = '';

    const DEFAULT_LIMIT = 10;
    const DEFAULT_ORDER = ' id DESC ';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'link', 'category_id'], 'required'],
            [['category_id', 'height', 'width', 'status', 'created_at', 'updated_at', 'viewed', 'ishot', 'homeslide'], 'integer'],
            [['description'], 'string'],
            [['name', 'alias', 'link', 'length', 'embed', 'avatar_path', 'avatar_name', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 500],
            [['avatar', 'province_id', 'author'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên video',
            'alias' => 'Alias',
            'category_id' => 'Danh mục',
            'province_id' => 'Tỉnh/thành phố',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả chi tiết',
            'link' => 'Đường link video',
            'embed' => 'Embed',
            'height' => 'Height',
            'width' => 'Width',
            'status' => 'Trạng thái',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'viewed' => 'Viewed',
            'avatar' => 'Ảnh đại diện',
            'ishot' => 'Video nổi bật',
            'homeslide' => 'Hiển thị slide trang chủ',
            'author' => 'Người đăng',
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
     * 
     * @return type
     */
    public function getProvince() {
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }

    /**
     * 
     * @return type
     */
    public function getCategory() {
        return $this->hasOne(VideoCategory::className(), ['id' => 'category_id']);
    }

    public static function getVideohot($options = []) {
        $limit = self::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        return Video::find()
                        ->where('status=:status AND ishot=:ishot', [':status' => ClaLid::STATUS_ACTIVED, ':ishot' => ClaLid::ISHOT])
                        ->limit($limit)
                        ->orderBy('id DESC')
                        ->asArray()
                        ->all();
    }
    
    public static function getVideoHomeSlide($options = []) {
        $limit = self::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        return Video::find()
                        ->where('status=:status AND homeslide=:homeslide', [':status' => ClaLid::STATUS_ACTIVED, ':homeslide' => ClaLid::STATUS_ACTIVED])
                        ->limit($limit)
                        ->orderBy('id DESC')
                        ->asArray()
                        ->all();
    }

    public static function getVideoRelation($options = []) {
        $limit = self::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        if (isset($options['video_id']) && $options['video_id']) {
            $condition .= ' AND id<>:id';
            $params[':id'] = $options['video_id'];
        }
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= ' AND category_id=:category_id';
            $params[':category_id'] = $options['category_id'];
        }
        if (isset($options['province_id']) && $options['province_id']) {
            $condition .= ' AND province_id=:province_id';
            $params[':province_id'] = $options['province_id'];
        }
        return Video::find()
                        ->where($condition, $params)
                        ->limit($limit)
                        ->asArray()
                        ->all();
    }

    public static function getVideoInCategory($category_id, $options = []) {
        $category_id = (int) $category_id;
        if (!$category_id) {
            return array();
        }
        //
        $condition = 'status=:status AND category_id=:category_id';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED,
            ':category_id' => $category_id
        ];
        //
        if (!isset($options['limit'])) {
            $options['limit'] = ClaLid::DEFAULT_LIMIT;
        }
        $order = 'id DESC';
        //
        $offset = ($options['page'] - 1) * $options['limit'];
        //
        $videos = (new \yii\db\Query())->select('*')
                ->from('video')
                ->where($condition, $params)
                ->orderBy($order)
                ->limit($options['limit'])
                ->offset($offset)
                ->all();
        return $videos;
    }

    public static function countVideoInCategory($category_id) {
        return Video::find()
                        ->where('status=:status AND category_id=:category_id', ['status' => ClaLid::STATUS_ACTIVED, ':category_id' => $category_id])
                        ->count();
    }

    public static function getVideoInProvince($province_id, $options = []) {
        $province_id = (int) $province_id;
        if (!$province_id) {
            return array();
        }
        //
        $condition = 'status=:status AND province_id=:province_id';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED,
            ':province_id' => $province_id
        ];
        //
        if (!isset($options['limit'])) {
            $options['limit'] = ClaLid::DEFAULT_LIMIT;
        }
        $order = 'id DESC';
        //
        $offset = ($options['page'] - 1) * $options['limit'];
        //
        $videos = (new \yii\db\Query())->select('*')
                ->from('video')
                ->where($condition, $params)
                ->orderBy($order)
                ->limit($options['limit'])
                ->offset($offset)
                ->all();
        return $videos;
    }

    public function countVideoInProvince($province_id) {
        return Video::find()
                        ->where('status=:status AND province_id=:province_id', ['status' => ClaLid::STATUS_ACTIVED, ':province_id' => $province_id])
                        ->count();
    }

    public static function getVideoByCondition($options = []) {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        //
        if (isset($options['key']) && $options['key']) {
            $condition .= ' AND name LIKE :key';
            $params[':key'] = '%' . $options['key'] . '%';
        }
        if (isset($options['cid']) && $options['cid']) {
            $condition .= ' AND category_id=:category_id';
            $params[':category_id'] = $options['cid'];
        }
        //
        if (!isset($options['limit'])) {
            $options['limit'] = ClaLid::DEFAULT_LIMIT;
        }
        //
        $offset = ($options['page'] - 1) * $options['limit'];
        //
        $data = (new \yii\db\Query())->select('*')
                ->from('video')
                ->where($condition, $params)
                ->orderBy('id DESC')
                ->limit($options['limit'])
                ->offset($offset)
                ->all();
        return $data;
    }

    public static function countVideoByCondition($options = []) {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        //
        if (isset($options['key']) && $options['key']) {
            $condition .= ' AND name LIKE :key';
            $params[':key'] = '%' . $options['key'] . '%';
        }
        if (isset($options['cid']) && $options['cid']) {
            $condition .= ' AND category_id=:category_id';
            $params[':category_id'] = $options['cid'];
        }
        return Video::find()
                        ->where($condition, $params)
                        ->count();
    }

     public static function getVideoByAttr($options = [])
    {
        $where ="status = 1";
        if (isset($options['attr']) && $options['attr']) {
            foreach ($options['attr'] as $key => $value) {
                $where .= " AND $key = '$value' ";
            }
        }
        if (isset($options['_video']) && $options['_video']) {
           $where .= " AND id <> ".$options['_video'];
        }

        $order = Video::DEFAULT_ORDER;
        if (isset($options['order']) && $options['order']) {
            $order = $options['order'];
        }

        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        //
        $news = (new Query())->select('*')
                ->from('video')
                ->where($where)
                ->orderBy($order)
                ->limit($limit)
                ->all();
        return $news;
    }

    static function getDataSearch($option) {
        if ($option['tag']) {
            $tag = $option['tag'];
            $tag_set = \common\models\product\Product::changeKey($option['tag']);
        } else {
            $tag = null;
            $tag_set = null;
        }
        if (isset($option['limit'])) {
            $limit = $option['limit'];
        } else {
            $limit = \common\models\Search::LIMIT;
        }
        if (isset($option['page'])) {
            $page = $option['page'];
        } else {
            $page = 1;
        }
        $data = (new \yii\db\Query())
                ->from('video')
                ->select('id, name as title, alias, meta_keywords, short_description as content, avatar_name, avatar_path')
                ->where(['like', 'alias', $tag_set])
                ->orWhere(['like', 'meta_keywords', $tag])
                ->orderBy('id DESC')
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['type'] = \common\models\Search::getTypeName(\common\models\Search::TYPE_VIDEO);
            $data[$i]['link'] = \yii\helpers\Url::to(['/media/video/detail', 'id' => $data[$i]['id'], 'alias' => $data[$i]['alias']]);
        }
        $tg['data'] = $data;
        $count = (new \yii\db\Query())
                ->from('video')
                ->where(['like', 'alias', $tag_set])
                ->orWhere(['like', 'meta_keywords', $tag])
                ->count();
        $tg['pagination'] = new \yii\data\Pagination([
            'defaultPageSize' => $limit,
            'totalCount' => $count,
        ]);

        return $tg;
    }

    /**
     * @param array $options
     * @return array
     */
    public static function getVideo($options = []) {
        
        $condition ='1 = 1';
        $params ='';
        if(!isset($options['status'])) {
            $condition = 'status=:status';
            $params = [
                ':status' => ClaLid::STATUS_ACTIVED,
            ];
        }
        if (isset($options['keyword']) && $options['keyword']) {
            $first_character = substr($options['keyword'], 0, 1);
            if (isset($first_character) && $first_character == '#') {
                $options['keyword'] = ltrim($options['keyword'], '#');
                $condition .= ' AND t.id=:id';
                $params[':id'] = $options['keyword'];
            } else {
                $key = self::changeKey($options['keyword']);
                // echo $key; die();
                $condition .= ' AND t.alias LIKE :name';
                $params[':name'] = '%' . $key . '%';
            }
        }
        //
        if (isset($options['ishot']) && $options['ishot']) {
            $condition .= ' AND t.ishot=:ishot';
            $params[':ishot'] = $options['ishot'];
        }
        //
        $limit = isset($options['limit']) ? $options['limit'] : ClaLid::DEFAULT_LIMIT;
        if (isset($options['per-page']) && $options['per-page']) {
            $limit = $options['per-page'];
        }
        //
        if (isset($options['page'])) {
            $offset = ($options['page'] - 1) * $limit;
        } else {
            $offset = 0;
        }
        $order = self::DEFAULT_ORDER;

        if (isset($options['id']) && $options['id'] && is_array($options['id'])) {
            $ids_strings = implode(',', $options['id']);
            $condition .= " AND id IN ($ids_strings)";
        }

        //
        if (isset($options['count'])) {
            $total = self::find()->where($condition, $params)
                    ->count();
            return $total;
        }
        $data = self::find()->where($condition, $params)
                ->orderBy($order)
                ->limit($limit)
                ->offset($offset)
                ->all();
       
        return $data;
    }


}
