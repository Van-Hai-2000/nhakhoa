<?php

namespace common\models\branch;

use common\components\ClaActiveRecordLog;
use common\models\news\NewsCategory;
use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 */
class Branch extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number_address','code'], 'required'],
            [['name','code'], 'string', 'max' => 255],
            [['number_address'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên chi nhánh',
            'address' => 'Địa chỉ',
            'number_address' => 'Số địa chỉ',
            'code' => 'Viết tắt tên đường',
        ];
    }

    static function getBranch(){
        $data = self::find()->asArray()->all();
        return array_column($data,'name','id');
    }
}
