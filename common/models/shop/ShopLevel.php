<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "{{%shop_level}}".
 *
 * @property string $id
 * @property string $name
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $created_time
 * @property string $modified_time
 * @property string $image_path
 * @property string $image_name
 * @property string $link
 */
class ShopLevel extends \yii\db\ActiveRecord {

    public $avatar;
    public $image;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%shop_level}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['name'], 'required'],
                [['created_time', 'modified_time'], 'integer'],
                [['name', 'avatar_path', 'avatar_name', 'image_path', 'image_name', 'link'], 'string', 'max' => 255],
                [['avatar', 'image'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên cấp',
            'avatar' => 'Avatar',
            'image' => 'Ảnh lớn',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'image_path' => 'Image Path',
            'image_name' => 'Image Name',
            'link' => 'Đường dẫn bài viết',
        ];
    }

    public static function optionShopLevel() {
        $data = ShopLevel::find()->asArray()->all();
        return array_column($data, 'name', 'id');
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if($this->isNewRecord) {
                $this->created_time = time();
            }
            $this->modified_time = time();
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->name);
            return true;
        } else {
            return false;
        }
    }

}
