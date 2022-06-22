<?php

namespace common\models\review;

use common\components\ClaLid;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "customer_reviews".
 *
 * @property string $id
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $review
 * @property string $customer_name
 * @property string $created_time
 * @property double $score
 * @property string $review_en
 * @property string $customer_name_en
 * @property string $avatar
 */
class CustomerReviews extends \yii\db\ActiveRecord
{
    public $avatar;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review', 'customer_name', 'score','title', 'title_en'], 'required'],
            [['created_time'], 'integer'],
            [['score'], 'number'],
            [['avatar_path', 'avatar_name', 'customer_name', 'customer_name_en', 'customer_address', 'customer_address_en'], 'string', 'max' => 255],
            [['review', 'review_en'], 'string', 'max' => 2000],
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
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'review' => 'Đánh giá',
            'customer_name' => 'Tên người đánh giá',
            'created_time' => 'Created Time',
            'score' => 'Số sao',
            'review_en' => 'Đánh giá (Tiếng anh)',
            'customer_name_en' => 'Tên người đánh giá (Tiếng anh)',
            'customer_address' => 'Địa chỉ',
            'customer_address_en' => 'Địa chỉ (Tiếng anh)',
            'title' => 'Tiêu đê',
            'title_en' => 'Tiêu đê (Tiếng anh)',
        ];
    }

    /**
     * @inheritdoc
     */

    public static function getCustomerReview($options = [])
    {
        $limit = ClaLid::DEFAULT_LIMIT;
        
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        
        if (isset($_GET['page']) && $_GET['page']) {
            $page = $_GET['page']-1;
        } else {
            $page = 0;
        }
        if(isset($options['count']) && $options['count']) {
            return $data = (new Query())->select('*')
            ->from('customer_reviews')
            ->where('status=:status ', [':status' => ClaLid::STATUS_ACTIVED])
            ->count();
        }
        $data = (new Query())->select('*')
            ->from('customer_reviews')
            ->where('status=:status', [':status' => ClaLid::STATUS_ACTIVED])
            ->offset($page)
            ->limit($limit)
            ->all();

        return $data;
    }


}
