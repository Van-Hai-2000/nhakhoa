<?php

namespace common\models\transport;

use Yii;

/**
 * This is the model class for table "transport".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 */
class Transport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_at'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['note'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public static function getAll() {
        return Transport::find()->where(['status' => 1])->all();
    }

    public static function getName($id) {
        $model = self::findOne($id);
        return $model ? $model->name : '';
    }
}
