<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "cer_xts_shop".
 *
 * @property integer $id
 * @property integer $cer_xts_id
 * @property integer $shop_id
 * @property string $username
 * @property string $password
 * @property integer $created_at
 * @property integer $updated_at
 */
class CerXtsShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cer_xts_shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'cer_xts_id', 'shop_id'], 'required'],
            [['cer_xts_id', 'shop_id', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cer_xts_id' => 'Cer Xts ID',
            'shop_id' => 'Shop ID',
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getOptionsCodeDiary($shop_id)
    {
        $options = [];
        $xts = new \common\components\xts\ClaXts($shop_id);
        if ($xts->code) {
            $options = $xts->getOptionsCodeDiary();
        }
        return $options;
    }

    public function getDiaryDetail($shop_id, $code)
    {
        $options = [];

        $xts = new \common\components\xts\ClaXts($shop_id);
        if ($xts->code) {
            $options = $xts->getDiaryDetail($code);
        }
        return $options;
    }

    public function getInfoCompany($shop_id)
    {
        $options = [];
        $xts = new \common\components\xts\ClaXts($shop_id);
        if ($xts->code) {
            $options = $xts->getInfoCompany($shop_id);
        }
        return $options;
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

    function getOne($options)
    {
        $model = self::find()->where($options)->one();
        if (!$model) {
            $model = new self();
            $model->attributes = $options;
        }
        return $model;
    }

    public function afterDelete()
    {
        Yii::$app->session->open();
        $_SESSION['list_code_xts_ss'] = [];
        return parent::afterDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->session->open();
        $_SESSION['list_code_xts_ss'] = [];
        return parent::afterSave($insert, $changedAttributes);
    }
}
