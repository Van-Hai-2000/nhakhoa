<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "ward".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $latlng
 * @property integer $district_id
 */
class EmailContact extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'email_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email'], 'required'],
            [['email'], 'unique'],
            [['email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'created_at' => 'Ngày tạo',
        ];
    }

}
