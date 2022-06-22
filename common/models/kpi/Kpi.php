<?php

namespace common\models\kpi;

use Yii;

/**
 * This is the model class for table "kpi".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property integer $dinh_muc_khoan
 * @property integer $created_at
 * @property integer $updated_at
 */
class Kpi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kpi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dinh_muc_khoan', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên chỉ số KPI',
            'description' => 'Mô tả chỉ số',
            'dinh_muc_khoan' => 'Định mức khoán',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getKpi() {
        return $this->hasMany(KpiUser::className(), ['kpi_id' => 'id']);
    }
}
