<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/20/2018
 * Time: 5:05 PM
 */
namespace common\models\cron;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class CronEmail extends ActiveRecord
{
    public static function tableName() {
        return 'cron_email';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email', 'content'], 'required','message' => '{attribute} không được bỏ trống'],
            [['created_at', 'updated_at'], 'integer'],
            [['content','email'], 'string']
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */


}