<?php

namespace common\models\form;

use Yii;

/**
 * This is the model class for table "{{%form_register_buy}}".
 *
 * @property integer $id
 * @property integer $user_buy_id
 * @property integer $user_news_id
 * @property string $price
 * @property integer $quantity
 * @property integer $status
 * @property integer $viewed
 * @property integer $created_at
 * @property integer $updated_at
 */
class FormRegisterBuy extends \common\models\ActiveRecordC
{

    public $type_price = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form_register_buy}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_buy_id', 'user_news_id', 'price', 'quantity', 'news_id'], 'required'],
            [['user_buy_id', 'user_news_id', 'status', 'viewed', 'created_at', 'updated_at'], 'integer'],
            [['price', 'quantity', 'type_price'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_buy_id' => 'User Buy ID',
            'user_news_id' => 'User News ID',
            'price' => 'Giá mua',
            'quantity' => 'Số lượng',
            'status' => 'Status',
            'viewed' => 'Viewed',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'type_price' => 'Giá mua',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!$this->access()) {
                    return false;
                }
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public function access()
    {
        $check = \common\models\shop\Shop::checkAccountStatus($this->user_buy_id);
        if ($check) {
            return true;
        }
        $this->addError('user_buy_id', 'Tài khoản của quý khách hiện không thể thực hiện chức năng này. Vui lòng nhập đầy đủ thông tin xác thực và gủi yêu cầu xác thực tới BQT để có thể thực hiện chức năng này.');
        return false;
    }

    static function getListRegisted($user_id)
    {
        $ls = self::find()->where(['user_buy_id' => $user_id])->andWhere(" status != 0")->asArray()->all();
        return $ls ? array_column($ls, 'news_id') : [];
    }

    static function getOne($options)
    {
        $model = self::find()->where($options)->one();
        if (!$model) {
            $model = new self();
            $model->attributes = $options;
        }
        return $model;
    }

    public function optionStatus()
    {
        return [
            0 => 'Hủy bỏ',
            1 => 'Xác nhận',
            2 => 'Chờ xác nhận',
        ];
    }

    public function showStatus($status = null)
    {
        $status = ($status !== null) ? $status : $this->status;
        $arr = $this->optionStatus();
        return (isset($arr[$status])) ? $arr[$status] : 'N/A';
    }

    public function beforeAttr($query, &$options)
    {
        $query->select('form_register_buy.*, user.username, user.phone, news.title')->leftJoin('user', 'user.id = form_register_buy.user_buy_id');
        $query->leftJoin('news', 'news.id = form_register_buy.news_id');
        if (isset($options['attr']['key'])) {
            $key = $options['attr']['key'];
            $query->andWhere(" user.username LIKE '%$key%' OR user.phone LIKE '%$key%' OR  news.title LIKE '%$key%' OR form_register_buy.price LIKE '%$key%' OR form_register_buy.quantity LIKE '%$key%'");
            unset($options['attr']['key']);
        }
        return $query;
    }

    static function countUnreadNotifications()
    {
        $model = new \common\models\form\FormRegisterBuy();
        $get['user_news_id'] = Yii::$app->user->id;
        $get['viewed'] = 0;
        return $model->getByAttr([
            'count' => 1,
            'attr' => $get
        ]);
    }

    static function getNews($id)
    {
        return \common\models\news\News::findOne(['id' => $id, 'category_id' => \common\models\news\News::CATEGORY_SELL]);
    }
}
