<?php

namespace common\models\sync;

use common\components\ClaLid;
use Yii;
use yii\db\Query;

class Dmqttg extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dmqttg';
    }

    public static function getDb() {
        return \Yii::$app->db2;  // use the "db2" application component
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [[['QttgID'], 'safe']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'QttgID' => 'Mã quy tắc',
        ];
    }

}

?>