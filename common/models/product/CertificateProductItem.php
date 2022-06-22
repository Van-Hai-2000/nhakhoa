<?php

namespace common\models\product;

use yii\db\Query;
use Yii;

/**
 * This is the model class for table "{{%certificate_product_item}}".
 *
 * @property string $id
 * @property integer $product_id
 * @property string $avatar_name
 * @property integer $certificate_product_id
 * @property string $avatar_path
 */
class CertificateProductItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificate_product_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'avatar_name', 'certificate_product_id', 'avatar_path'], 'required'],
            [['product_id', 'certificate_product_id'], 'integer'],
            [['avatar_name', 'avatar_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'avatar_name' => 'Avatar Name',
            'certificate_product_id' => 'Certificate Product ID',
            'avatar_path' => 'Avatar Path',
        ];
    }
    public static function getUpdateProduct($product_id)
    {
        $certificates =  (new Query())
            ->from('certificate_product_item')
            ->where(['product_id' => $product_id])
            ->all();
        $data = [];
        if ($certificates) {
            foreach ($certificates as $certificate) {
                $data[$certificate['certificate_product_id']] =  $certificate;
            }
        }
        return $data;
    }

    function getCode($product_id, $certificate_product_id)
    {
        $model = self::find()->where(['product_id' => $product_id, 'certificate_product_id' => $certificate_product_id])->one();
        if ($model && $model->code) {
            return $model->code;
        }
        return '';
    }
}
