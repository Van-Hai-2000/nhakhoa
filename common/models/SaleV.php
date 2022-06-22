<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sale_v}}".
 *
 * @property integer $id
 * @property integer $status
 * @property string $percent
 * @property integer $time_start
 * @property integer $time_end
 * @property integer $user_admin
 */
class SaleV extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_v}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'time_start', 'time_end'], 'safe', 'on' => ['affiliate']],
            [['percent'], 'number', 'on' => ['affiliate']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Trạng thái khuyến mãi',
            'percent' => 'Phần trăm khuyến mãi',
            'time_start' => 'Thời gian bắt đầu',
            'time_end' => 'Thời gian kết thúc',
        ];
    }

    static public function getNow()
    {
        $model = self::find()->one();
        if ($model->isSale()) {
            return $model;
        }
        return false;
    }

    static public function getNear()
    {
        $model = self::find()->one();
        if ($model->time_start > time()) {
            return $model;
        }
        return false;
    }

    public function isSale()
    {
        if ($this->status == 1 && $this->time_start <= time() && $this->time_end >= time()) {
            return true;
        }
        return false;
    }

    public function getTextSale(Type $var = null)
    {
        return "Nhận VOUCHER khuyến mãi(Vs)";
    }

    public function getTextSale2(Type $var = null)
    {
        return "Từ " . date('H:i d-m-Y', $this->time_start) . " đến " . date('H:i d-m-Y', $this->time_end) . ", quý khách có thể nạp Vs với khuyến mãi " . $this->percent . "%";
    }
}
