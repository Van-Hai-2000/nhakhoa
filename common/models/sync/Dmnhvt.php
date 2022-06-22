<?php

namespace common\models\sync;

use common\components\ClaLid;
use Yii;
use yii\db\Query;

class Dmnhvt extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dmnhvt2';
    }

    public static function getDb() {
        return \Yii::$app->db2;  // use the "db2" application component
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [[['Nhvt2ID'], 'safe']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'Nhvt2ID' => 'Mã nhóm vật tư',
        ];
    }

}

?>