<?php

namespace common\models\medicine;

use common\components\ClaActiveRecordLog;
use Yii;

/**
 * This is the model class for table "medicine".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property string $category_track
 * @property string $code
 * @property double $price
 * @property integer $status
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $avatar_id
 * @property string $short_description
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Medicine extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medicine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','price'], 'required'],
            [['id', 'category_id', 'status', 'avatar_id', 'created_at', 'updated_at','ckedit_desc'], 'integer'],
            [['price','price_market'], 'number'],
            [['short_description', 'description'], 'string'],
            [['name', 'category_track', 'code', 'avatar_path', 'avatar_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên',
            'price_market' => 'Giá nhập vào',
            'category_id' => 'Danh mục',
            'category_track' => 'Category Track',
            'code' => 'Mã',
            'price' => 'Giá',
            'status' => 'Trạng thái',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'avatar_id' => 'Avatar ID',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'ckedit_desc' => 'Sử dụng trình soạn thảo',
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

    public function getCategory()
    {
        return $this->hasOne(MedicineCategory::className(), ['id' => 'category_id'])->select('id,name');
    }

    static function getMedicine(){
        $medicine = self::find()->where(['status' => 1])->asArray()->all();
        return array_column($medicine,'name','id');
    }

    public static function getImages($id)
    {
        $result = [];
        if (!$id) {
            return $result;
        }
        $result = (new \yii\db\Query())->select('*')
            ->from('medicine_image')
            ->where('medicine_id=:medicine_id', [':medicine_id' => $id])
            ->orderBy('order ASC, created_at DESC')
            ->all();
        return $result;
    }
}
