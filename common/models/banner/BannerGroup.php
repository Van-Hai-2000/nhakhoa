<?php

namespace common\models\banner;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "banner_group".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $width
 * @property string $height
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class BannerGroup extends \common\models\ActiveRecordC
{

    const BANNER_MAIN_GOLD = 5;
    const BANNER_EVENT_GOLD = 6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'width', 'height'], 'required'],
            [['width', 'height', 'created_at', 'updated_at', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên nhóm banner',
            'description' => 'Mô tả',
            'width' => 'Chiều rộng banner',
            'height' => 'Chiều cao banner',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Trạng thái',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @hungtm
     * return array options banner group
     */
    public static function optionsBannerGroup()
    {
        $data = (new Query())->select('*')
            ->from('banner_group')
            ->all();
        $promt = array('' => ' --- Chọn nhóm banner --- ');
        return $promt + array_column($data, 'name', 'id');
    }
}
