<?php

namespace common\models\rating;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "rating".
 *
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $address
 * @property string $email
 * @property integer $rating
 * @property integer $type
 * @property string $object_id
 * @property string $content
 * @property string $created_at
 * @property integer $status
 */
class Rating extends \yii\db\ActiveRecord {

    const RATING_PRODUCT = 1; // đánh giá sản phẩm
    const RATING_SHOP = 2; // đánh giá shop
    const RATING_PRODUCT_ORDER = 3; // đánh giá shop

    /**
     * @inheritdoc
     */

    public static function tableName() {
        return 'rating';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'address', 'email', 'rating', 'object_id', 'content'], 'required'],
            [['user_id', 'rating', 'type', 'object_id', 'created_at', 'status', 'order_item_id'], 'integer'],
            [['content', 'name', 'address', 'email'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'rating' => 'Điểm vote',
            'type' => 'Loại',
            'object_id' => 'ID',
            'content' => 'Nội dung',
            'created_at' => 'Thời gian',
            'status' => 'Trạng thái',
            'name' => 'Họ Tên',
            'address' => 'Địa chỉ',
            'email' => 'Email'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function getType($id = -1) {
        $arr = [
            '' => Yii::t('app', 'select_type'),
            self::RATING_PRODUCT => Yii::t('app', 'product'), // đánh giá sản phẩm
            self::RATING_SHOP => Yii::t('app', 'shop'), // đánh giá gian hàng
        ];

        return isset($arr[$id]) ? $arr[$id] : $arr;
    }

    public static function getTypeNull($id = -1) {
        $arr = [
            self::RATING_PRODUCT => Yii::t('app', 'product'), // đánh giá sản phẩm
            self::RATING_SHOP => Yii::t('app', 'shop'), // đánh giá gian hàng
        ];
        return isset($arr[$id]) ? $arr[$id] : 'N/A';
    }

    public static function getRatings($type, $object_id) {
        $data =[];
        if($type < 2) {
            $data = (new Query())->select('r.*, t.username, t.avatar_name, t.avatar_path')
                ->from('rating r')
                ->join('LEFT JOIN', 'user t', 'r.user_id=t.id')
                ->where('r.type=:type AND r.object_id=:object_id AND r.status = 1', [':type' => $type, ':object_id' => $object_id])
                ->orderBy('r.id DESC')
                ->all();
        } else {
            if($type == 2) {
                $type = 1;
                $data = (new Query())->select('r.*, t.username, t.avatar_name, t.avatar_path')
                    ->from('rating r')
                    ->join('LEFT JOIN', 'user t', 'r.user_id=t.id')
                    ->join('LEFT JOIN', 'product p', 'r.object_id=p.id')
                    ->where("r.type='$type' AND r.status = 1 AND p.shop_id = '$object_id'")
                    ->orderBy('r.id DESC')
                    ->all();
            }
        }
        
        return $data;
    }

    public static function getRatingTypes($types, $object_id) {
        $data = (new Query())->select('r.*, t.username, t.avatar_name, t.avatar_path')
                ->from('rating r')
                ->join('LEFT JOIN', 'user t', 'r.user_id=t.id')
                ->where('r.type IN ('.implode(',', $types).') AND r.object_id=:object_id AND r.status = 1', [':object_id' => $object_id])
                ->orderBy('r.id DESC')
                ->all();
        return $data;
    }

    public static function getRatingsByOrder($order_item_id) {
        $data = (new Query())->select('r.*, t.username, t.avatar_name, t.avatar_path')
                ->from('rating r')
                ->join('LEFT JOIN', 'user t', 'r.user_id=t.id')
                ->where('r.status = 1 AND r.order_item_id ='.$order_item_id)
                ->orderBy('r.id DESC')
                ->all();
        return $data;
    }
    

    public static function getAvgRating($type, $object_id) {
        $avg = (new Query())->select('AVG(rating)')
                ->from('rating')
                ->where('status = 1 AND type=:type AND object_id=:object_id', [':type' => $type, ':object_id' => $object_id])
                ->scalar();
        return $avg;
    }

    public static function countRate($order_item_id, $object_id) {
        return Rating::find()->where(['order_item_id' => $order_item_id, 'object_id' => $object_id, 'status' => 1])->count();
    }

}
