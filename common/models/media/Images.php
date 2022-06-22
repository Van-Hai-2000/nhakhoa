<?php

namespace common\models\media;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $display_name
 * @property string $alias
 * @property integer $height
 * @property integer $width
 * @property integer $created_at
 */
class Images extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'images';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['height', 'width', 'created_at'], 'integer'],
            [['name', 'path', 'display_name', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'path' => 'Path',
            'display_name' => 'Display Name',
            'alias' => 'Alias',
            'height' => 'Height',
            'width' => 'Width',
            'created_at' => 'Created Time',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function getImageExtension() {
        return ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico'];
    }

}
