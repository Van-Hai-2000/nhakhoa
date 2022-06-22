<?php

namespace common\models;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "{{%bank}}".
 *
 * @property string $id
 * @property string $name
 * @property string $avatar_path
 * @property string $avatar_name
 * @property integer $created_at
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bank}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at'], 'integer'],
            [['name', 'avatar_path', 'avatar_name'], 'string', 'max' => 255],
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
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'created_at' => 'Created At',
        ];
    }

    public static function optionsBank() {
        $data = (new Query())->select('*')
                ->from('bank')
                ->all();
        $add[''] = Yii::t('app', 'select_bank');
        $add[0] = '1';
        $data = array_merge($add, array_column($data, 'name', 'id'));
        unset($data[0]);
        return $data;
    }
}
