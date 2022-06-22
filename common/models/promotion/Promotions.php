<?php

namespace common\models\promotion;

use Yii;

/**
 * This is the model class for table "{{%promotions}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $sortdesc
 * @property string $description
 * @property integer $status
 * @property integer $startdate
 * @property integer $enddate
 * @property string $alias
 * @property boolean $showinhome
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $created_time
 * @property string $image_path
 * @property string $image_name
 * @property boolean $ishot
 * @property integer $category_id
 * @property integer $order
 */
class Promotions extends \yii\db\ActiveRecord
{
    public $avatar;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['status', 'startdate', 'enddate', 'created_time', 'category_id', 'order'], 'integer'],
            [['showinhome', 'ishot'], 'boolean'],
            [['name', 'alias', 'meta_title', 'image_path'], 'string', 'max' => 255],
            [['sortdesc', 'meta_keywords', 'meta_description'], 'string', 'max' => 500],
            [['image_name'], 'string', 'max' => 200],
            [['avatar', 'time_space'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên khuyễn mãi',
            'sortdesc' => 'Mô tả ngắn',
            'description' => 'Mô tả chi tiết',
            'status' => 'Trạng thái',
            'startdate' => 'Thời gian bắt đầu',
            'enddate' => 'Thời gian kết thúc',
            'alias' => 'Alias',
            'showinhome' => 'Hiện thị trang chủ',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_time' => 'Created Time',
            'image_path' => 'Image Path',
            'image_name' => 'Image Name',
            'ishot' => 'Ishot',
            'category_id' => 'Category ID',
            'order' => 'Order',
            'time_space' => 'Chia khoảng trong ngày',
            'avatar' => 'Ảnh bìa',
            'avatar' => 'Ảnh bìa',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_time = time();
            }
            // Alias
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->name);
            //
            return true;
        } else {
            return false;
        }
    }

    public static function getOne() {
        Yii::$app->session->open();
        $tg = [];
        if(isset($_SESSION['promotion_now']) && $_SESSION['promotion_now']) {
            if(time() - $_SESSION['promotion_now']['time'] < 10) {
                return $_SESSION['promotion_now']['data'];
            }
        }
        $_SESSION['promotion_now']['data'] = Promotions::find()->where(
                                                    'status ='.\common\components\ClaLid::STATUS_ACTIVED.
                                                    ' AND startdate <='.time().
                                                    ' AND enddate >'.time()
                                                )->orderBy('order ASC')
                                            ->one();
        $_SESSION['promotion_now']['time'] = time();
        return $_SESSION['promotion_now']['data'];
    }

    public function getTimeNow() {
        date_default_timezone_set("Asia/Bangkok");
        $list_time = explode(' ', $this->time_space);
        for ($i=0; $i < count($list_time); $i++) {
            if( (!isset($list_time[$i+1])) || (isset($list_time[$i]) && $list_time[$i+1] > date('H',time()))) { 
                return strtotime($list_time[$i].':00 '.date('d-m-Y', time()));
            }
        }
        return null;
    }

    public function getHourNow() {
        date_default_timezone_set("Asia/Bangkok");
        $list_time = explode(' ', $this->time_space);
        for ($i=0; $i < count($list_time); $i++) {
            if( (!isset($list_time[$i+1])) || (isset($list_time[$i]) && $list_time[$i+1] > date('H',time()))) { 
                return $list_time[$i];
            }
        }
        return 0;
    }

    public function getHourBefore($hour) {
        date_default_timezone_set("Asia/Bangkok");
        $list_time = explode(' ', $this->time_space);
        for ($i=0; $i < count($list_time); $i++) {
            if($list_time[$i] == $hour) { 
                return ($i == 0) ? $list_time[count($list_time) - 1] : $list_time[$i - 1];
            }
        }
        return 0;
    }

    public function getHourAfter($hour) {
        date_default_timezone_set("Asia/Bangkok");
        $list_time = explode(' ', $this->time_space);
        $hour_next = \common\models\promotion\ProductToPromotions::getTimeSpaceStart($list_time[0], date('d-m-Y',time()+60*60*24));
        for ($i=0; $i < count($list_time); $i++) {
            if((isset($list_time[$i+1]) && $list_time[$i+1] > date('H',time()))) {
                $hour_next = \common\models\promotion\ProductToPromotions::getTimeSpaceStart($list_time[$i+1], date('d-m-Y', time()));
                break;
            }
        }
        return $hour_next;
    }

    public static function getPromotionNow() {
        return  self::getOne();
    }

    public static function getPromotionNowAll() {
        $data = [];
        if($promotion = self::getOne()) {
            $items =\common\models\promotion\ProductToPromotions::getPromotionInNow($promotion);
            if($items) {
                foreach ($items as $item) {
                    $data[$item['product_id']] = $item;
                }
            }
        }
        return $data;
    }
}
