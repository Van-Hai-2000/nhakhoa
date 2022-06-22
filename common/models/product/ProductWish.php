<?php

namespace common\models\product;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\ClaLid;
use yii\db\Query;

/**
 * This is the model class for table "brand".
 *
 * @property string $id
 * @property string $name
 * @property string $alias
 * @property string $avatar_path
 * @property string $avatar_name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ProductWish extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_wish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'user_id'], 'required'],
            [['created_at', 'product_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Ngày tạo',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function countByUser($user_id = null)
    {
        $user_id = $user_id ? $user_id : Yii::$app->user->id;
        return ProductWish::find()->where(['user_id' => $user_id])->count();
    }

    public static function getWishByAttr($options = [])
    {

        $where = "1 = 1";
        if (isset($options['attr']) && $options['attr']) {
            foreach ($options['attr'] as $key => $value) {
                $where .= " AND $key = '$value' ";
            }
        }

        if (isset($options['where']) && $options['where']) {
            $where .= " AND " . $options['where'];
        }

        $order = ClaLid::DEFAULT_ORDER;
        if (isset($options['order']) && $options['order']) {
            $order = $options['order'];
        }

        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        $page = Yii::$app->request->get('page', 1);
        //
        if (isset($options['count']) && $options['count']) {
            return (new Query())->select('product_id')
                ->from('product_wish')
                ->where($where)
                ->count();
        }
        $products = (new Query())->select('product_id')
            ->from('product_wish')
            ->where($where)
            ->orderBy($order)
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->all();

        return array_column($products, 'product_id');
    }

    public static function getWishAllByUser($options = [])
    {
        $user_id = isset($options['user_id']) ? $options['user_id'] :  Yii::$app->user->id;
        if ($user_id) {
            $products = (new Query())->select('product_id')
                ->from('product_wish')
                ->where(['user_id' => $user_id])
                ->all();
            return ($products) ? array_column($products, 'product_id') : [-1];
        }
        return [-1];
    }
}
