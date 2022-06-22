<?php

namespace common\models\product;

use common\components\ClaActiveRecordLog;
use Yii;
use yii\db\Query;
use common\components\ClaLid;
use common\models\rating\Rating;

/**
 * This is the model class for table "product".
 *
 * @property string $id
 * @property integer $brand
 * @property string $name
 * @property string $alias
 * @property string $category_id
 * @property string $category_track
 * @property string $code
 * @property string $barcode
 * @property string $price
 * @property string $price_market
 * @property integer $currency
 * @property string $quantity
 * @property integer $status
 * @property string $avatar_path
 * @property string $avatar_name
 * @property integer $avatar_id
 * @property integer $ishot
 * @property integer $price_market_af
 * @property string $viewed
 * @property string $created_at
 * @property string $updated_at
 * @property string $short_description
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $dynamic_field
 * @property string $order
 */
class ProductCategory extends ClaActiveRecordLog
{
    private $_cats = array('' => ' --- Chọn danh mục --- ');
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id','status','created_at', 'updated_at', 'avatar_id', 'order', 'number_time'], 'integer'],
            [['name'], 'required'],
            [['name', 'alias', 'avatar_path', 'avatar_name'], 'string', 'max' => 255],
            [['short_description', 'description', 'avatar', 'start_time', 'number_time', 'ckedit_desc'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nhóm thủ thuật',
            'alias' => 'Alias',
            'category_id' => 'Nhóm thủ thuật',
            'status' => 'Trạng thái',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả chi tiết',
            'order' => 'Số thứ tự',
            'avatar' => 'Ảnh đại diện',
            'start_time' => Yii::t('app', 'start_time_product'),
            'number_time' => Yii::t('app', 'number_time_product'),
            'ckedit_desc' => 'Sử dụng trình soạn thảo',
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
            $this->parent = 0;
            return true;
        } else {
            return false;
        }
    }

    public function optionsCategory($parent = 0, $level = 0, $without_self = false)
    {
        if (\Yii::$app->id == 'app-backend') {
            $data = ProductCategory::find()->where(['parent' => $parent])->orderBy('order')->all();
        } else {
            $data = ProductCategory::find()->where(['parent' => $parent, 'frontend_not_up' => 0])->orderBy('order')->all();
        }
        $glue = str_repeat('- - - ', $level);
        if ($data) {
            $level++;
            foreach ($data as $category) {
                $this->_cats[$category->id] = $glue . $category->name;
                $this->optionsCategory($category->id, $level);
            }
        }
        if ($without_self && $this->id) {
            unset($this->_cats[$this->id]);
        }
        return $this->_cats;
    }

    public static function getImages($id)
    {
        $result = [];
        if (!$id) {
            return $result;
        }
        $result = (new \yii\db\Query())->select('*')
            ->from('product_category_image')
            ->where('product_category_id=:product_category_id', [':product_category_id' => $id])
            ->orderBy('order ASC, created_at DESC')
            ->all();
        return $result;
    }

    static function getCategory(){
        return array_column(self::find()->where(['status' => 1])->asArray()->all(),'name','id');
    }

}
