<?php

namespace common\models;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "loai_mau".
 *
 * @property string $id
 * @property string $name
 * @property double $money
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class LoaiMau extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'loai_mau';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'money'], 'required'],
            [['money','money_market'], 'number'],
            [['status', 'created_at', 'updated_at','category_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên',
            'money' => 'Giá',
            'money_market' => 'Giá nhập',
            'status' => 'Trạng thái',
            'category_id' => 'Xưởng',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    static function getLoaimau(){
        $model = self::find()->where(['status' => 1])->asArray()->all();
        return array_column($model,'name','id');
    }

    public function getUser(){
        return $this->hasOne(UserAdmin::className(),['id' => 'category_id'])->select('fullname,id');
    }
}
