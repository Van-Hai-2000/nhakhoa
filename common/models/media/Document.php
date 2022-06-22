<?php

namespace common\models\media;

use Yii;

/**
 * This is the model class for table "documents".
 *
 * @property string $id
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'path', 'display_name', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'path' => 'Path',
            'display_name' => 'Display Name',
            'alias' => 'Alias',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
        ];
    }

    public static function getDocExtension() {
        return ['doc', 'docx', 'pdf', 'txt', 'csv', 'xls', 'xlsb', 'xlsx'];
    }
}
