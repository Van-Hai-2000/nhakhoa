<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%ques_ans}}".
 *
 * @property string $id
 * @property string $question
 * @property string $answer
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $cat_id
 */
class QuesAns extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ques_ans}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'answer', 'cat_id', 'status'], 'required'],
            [['created_at', 'updated_at', 'cat_id'], 'integer'],
            [['question_en', 'answer_en'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'question' => 'Câu hỏi',
            'answer' => 'Câu trả lời',
            'question_en' => 'question',
            'answer_en' => 'answer',
            'cat_id' => 'Danh mục',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
           
        ];
    }


    public function beforeSave($insert) {
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
}
