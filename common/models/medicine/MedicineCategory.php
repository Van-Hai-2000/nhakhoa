<?php

namespace common\models\medicine;

use common\components\ClaActiveRecordLog;
use Yii;

/**
 * This is the model class for table "medicine_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $parent
 * @property string $category_id
 * @property integer $status
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $avatar_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $short_description
 * @property string $description
 * @property integer $type
 * @property integer $start_time
 * @property integer $number_time
 * @property integer $ckedit_desc
 * @property integer $order
 */
class MedicineCategory extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medicine_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent', 'category_id', 'status', 'avatar_id', 'created_at', 'updated_at', 'type', 'start_time', 'number_time', 'order'], 'integer'],
            [['short_description', 'description'], 'string'],
            [['ckedit_desc'], 'safe'],
            [['name', 'alias', 'avatar_path', 'avatar_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên danh mục',
            'alias' => 'Alias',
            'parent' => 'Parent',
            'category_id' => 'Category ID',
            'status' => 'Trạng thái',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'avatar_id' => 'Avatar ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'short_description' => 'Short Description',
            'description' => 'Description',
            'type' => 'Type',
            'start_time' => 'Start Time',
            'number_time' => 'Number Time',
            'ckedit_desc' => 'Sử dụng trình soạn thảo',
            'order' => 'Sắp xếp',
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
            $this->parent = 0;
            return true;
        } else {
            return false;
        }
    }

    public function optionsCategory()
    {
        $model = self::find()->where(['status' => 1])->asArray()->all();
        return array_column($model,'name','id');
    }

    public static function getImages($id)
    {
        $result = [];
        if (!$id) {
            return $result;
        }
        $result = (new \yii\db\Query())->select('*')
            ->from('medicine_category_image')
            ->where('medicine_category_id=:medicine_category_id', [':medicine_category_id' => $id])
            ->orderBy('order ASC, created_at DESC')
            ->all();
        return $result;
    }

    static function getCategory(){
        return array_column(self::find()->where(['status' => 1])->asArray()->all(),'name','id');
    }
}
