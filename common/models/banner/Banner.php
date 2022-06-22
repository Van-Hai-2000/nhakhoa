<?php

namespace common\models\banner;

use Yii;
use yii\db\Query;
use common\components\ClaLid;

/**
 * This is the model class for table "banner".
 *
 * @property string $id
 * @property string $group_id
 * @property string $name
 * @property string $description
 * @property string $width
 * @property string $height
 * @property string $link
 * @property integer $order
 * @property integer $target
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Banner extends \common\models\ActiveRecordC
{

    public $default_order = 'order ASC, banner.id DESC';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'name', 'src'], 'required'],
            [['group_id', 'width', 'height', 'order', 'target', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'src', 'link'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
            [['category_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Nhóm banner',
            'name' => 'Tên banner',
            'src' => 'Chọn file',
            'description' => 'Mô tả',
            'width' => 'Chiều rộng banner',
            'height' => 'Chiều cao banner',
            'link' => 'Link banner',
            'order' => 'Thứ tự',
            'target' => 'Mở banner',
            'status' => 'Trạng thái',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'category_id' => Yii::t('app', 'menu'),
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
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cho phép những loại file nào
     * @return type
     */
    static function allowExtensions()
    {
        return array(
            'image/jpeg' => 'image/jpeg',
            'image/gif' => 'image/gif',
            'image/png' => 'image/png',
            'image/bmp' => 'image/bmp',
            'application/x-shockwave-flash' => 'application/x-shockwave-flash',
        );
    }

    /**
     * get banner from group id
     * @param type $group_id
     * @param type $options
     * @return type
     */
    public static function getBannerFromGroupId($group_id, $options = [])
    {
        $tgs = (new self)->getAllCached();
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        $data = [];
        $dem = 0;
        if ($tgs) foreach ($tgs as $item) {
            if ($item->group_id == $group_id && $item->status == 1) {
                $boolen = true;
                if (isset($options['category_id']) && $options['category_id']) {
                    if ($item->category_id != $options['category_id']) {
                        $boolen = false;
                    }
                }
                if ($boolen) {
                    $data[] = $item->attributes;
                    $dem++;
                }
                if ($dem >= $limit) {
                    return $data;
                }
            }
        }
        return $data;
    }

    public static function getBannerFromGroupIdQc($group_id, $options = [])
    {
        $tgs = (new self)->getAllCached();
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        $data = [];
        $dem = 0;
        if ($tgs) foreach ($tgs as $item) {
            if ($item->group_id == $group_id && $item->status == 1) {
                $boolen = true;
                if (isset($options['category_id']) && $options['category_id']) {
                    if ($item->category_id != $options['category_id']) {
                        $boolen = false;
                    }
                }
                if (isset($options['stt']) && $options['stt']) {
                    if ($item->order != $options['stt']) {
                        $boolen = false;
                    }
                }
                if ($boolen) {
                    $data[] = $item->attributes;
                    $dem++;
                }
                if ($dem >= $limit) {
                    return $data;
                }
            }
        }
        return $data;
    }

    public static function getBannerGroupId($group_id, $options = [])
    {
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        $data = (new Query())->select('*')
            ->from('banner')
            ->where(['group_id' => $group_id, 'status' => 1])
            ->limit($limit)
            ->orderBy('order ASC, id DESC')
            ->all();
        $tg = [];
        foreach ($data as $value) {
            $tg[$value['category_id']] = $value;
        }
        return $tg;
    }

    public function __get($name)
    {
        $tg = parent::__get($name);
        if ($name == 'src') {
            if ($tg[0] == '/') {
                $kt = \common\components\ClaHost::getImageHost() . $tg;
                return $kt;
            }
        }
        return $tg;
    }
}
