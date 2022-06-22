<?php

namespace common\models\test;

use common\components\ClaActiveRecordLog;
use Yii;

/**
 * This is the model class for table "table_test".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 */
class TableTest extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'table_test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Teen',
            'phone' => 'Phone',
            'email' => 'Email',
        ];
    }
}
