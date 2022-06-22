<?php

namespace common\models\rating;

use Yii;

/**
 * This is the model class for table "{{%rate_response}}".
 *
 * @property string $id
 * @property integer $rating_id
 * @property string $response
 * @property integer $user_response_id
 * @property string $user_response_name
 * @property string $created_at
 */
class RateResponse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rate_response}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rating_id', 'response', 'user_response_id', 'user_response_name', 'created_at'], 'required'],
            [['rating_id', 'user_response_id'], 'integer'],
            [['response'], 'string'],
            [['created_at'], 'safe'],
            [['user_response_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rating_id' => 'Rating ID',
            'response' => 'Response',
            'user_response_id' => 'User Response ID',
            'user_response_name' => 'User Response Name',
            'created_at' => 'Created At',
        ];
    }

    public static function getByRating($rating_id) {
        return RateResponse::find()->where(['rating_id' => $rating_id])->all();
    }   
}
