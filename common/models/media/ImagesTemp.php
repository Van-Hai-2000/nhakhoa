<?php

namespace common\models\media;

use Yii;

/**
 * This is the model class for table "images_temp".
 *
 * @property string $id
 * @property string $name
 * @property string $path
 * @property string $display_name
 * @property string $alias
 * @property integer $height
 * @property integer $width
 * @property integer $created_at
 */
class ImagesTemp extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'images_temp';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'required'],
            [['height', 'width', 'created_at'], 'integer'],
            [['id', 'name', 'path', 'display_name', 'alias'], 'string', 'max' => 255],
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

}
