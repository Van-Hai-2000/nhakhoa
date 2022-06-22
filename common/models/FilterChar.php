<?php

namespace common\models;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "{{%filter_char}}".
 *
 * @property string $id
 * @property string $characters
 */
class FilterChar extends \common\components\ClaActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filter_char}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['characters'], 'required'],
            [['characters'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'characters' => 'Characters',
        ];
    }

    public static function filterChars($string) { 
        $a = [];
        $b = [];
        $listchar = \common\components\ClaLid::getFilterChar();
        if($listchar) {
            foreach ($listchar as $char) {
                $arr = explode(',', $char['characters']);
                for ($i=0; $i < count($arr); $i++) { 
                    $a[] = $arr[$i];
                    $b[] = '***';
                }
            }
        }
        return str_replace($a,$b,$string);
    }
}
