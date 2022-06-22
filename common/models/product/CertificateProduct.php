<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "{{%certificate_product}}".
 *
 * @property string $id
 * @property string $name
 * @property string $avatar_path
 * @property string $avatar_name
 */
class CertificateProduct extends \yii\db\ActiveRecord
{
    public $avatar;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificate_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'avatar_path', 'avatar_name'], 'string', 'max' => 255],
            [['avatar'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên chứng chỉ',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'avatar' => 'icon',
        ];
    }
}
