<?php

namespace common\models\product;

use Yii;

class ProductCategoryType extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'status','bo_donvi_tiente'], 'integer'],
            [['name'], 'safe'],
            [['status'], 'default', 'value'=> 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên hình thức',
            'bo_donvi_tiente' => 'Bộ đơn vị tiền tệ',
            'created_at' => Yii::t('app', 'created_at'),
            'updated_at' => Yii::t('app', 'updated_at'),
            'status' => Yii::t('app', 'status'),
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

    public static function optionCategoryType()
    {
        $types = ProductCategoryType::find()->where(['status' => 1])->all();
        $data = ['' => '--- Chọn hình thức ---'];
        if ($types) {
            foreach ($types as $type) {
                $data[$type['id']] = $type['name'];
            }
        }
        return $data;
    }

}
