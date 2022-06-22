<?php

namespace common\models\product;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "brand".
 *
 * @property string $id
 * @property string $name
 * @property string $alias
 * @property string $avatar_path
 * @property string $avatar_name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Brand extends \yii\db\ActiveRecord {

    public $avatar = '';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'avatar_path', 'avatar_name', 'alias'], 'string', 'max' => 255],
            [['avatar'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên thương hiệu',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->name);
            return true;
        } else {
            return false;
        }
    }

    public static function getListBrand($isArray = true)
    {
        $data = Brand::find()->where(true)->all();
        if (count($data) && $isArray) {
            $aryBrand = ArrayHelper::map($data, 'id', 'name');
            return $aryBrand;
        }
        return $data;
    }

}
