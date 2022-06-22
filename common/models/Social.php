<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "social".
 *
 * @property string $id
 * @property string $hotline
 * @property string $facebook
 * @property string $youtube
 * @property string $twitter
 * @property string $instagram
 */
class Social extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hotline', 'facebook', 'youtube', 'twitter', 'instagram', 'pinterest', 'linkedin', 'tumblr', 'google'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hotline' => 'Hotline',
            'facebook' => 'Facebook',
            'youtube' => 'Youtube',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
        ];
    }
}
