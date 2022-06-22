<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_topsearch".
 *
 * @property string $id
 * @property string $keyword
 * @property integer $click
 * @property string $avatar_path
 * @property string $avatar_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $totalitem
 */
class ProductTopsearch extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_topsearch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['keyword'], 'required'],
                [['click', 'created_at', 'updated_at', 'totalitem'], 'integer'],
                [['keyword', 'avatar_path', 'avatar_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'keyword' => 'Từ khóa',
            'click' => 'Click',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Thời gian cập nhật',
            'totalitem' => 'Số kết quả'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            //
            return true;
        } else {
            return false;
        }
    }

    public static function statistics($options = []) {
        if (isset($options['keyword']) && $options['keyword']) {
            $model = ProductTopsearch::find()->where('keyword=:keyword', [
                        ':keyword' => $options['keyword']
                    ])->one();
            if ($model === NULL) {
                $model = new ProductTopsearch();
                $model->keyword = $options['keyword'];
                $model->totalitem = $options['totalitem'];
                $model->avatar_path = $options['avatar_path'];
                $model->avatar_name = $options['avatar_name'];
                $model->click = 1;
            } else {
                $model->totalitem = $options['totalitem'];
                $model->avatar_path = $options['avatar_path'];
                $model->avatar_name = $options['avatar_name'];
                $model->click += 1;
            }
            $model->save();
        }
    }
    
    public static function getTopsearch($options = []) {
        $limit = \common\components\ClaLid::DEFAULT_LIMIT;
        if(isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        $data = ProductTopsearch::find()
                ->orderBy('click DESC')
                ->limit($limit)
                ->asArray()
                ->all();
        return $data;
    }

}
