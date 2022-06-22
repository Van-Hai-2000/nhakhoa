<?php

namespace common\models\sync;

use common\components\ClaLid;
use Yii;
use yii\db\Query;

class Dmtiente extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dmtiente';
    }

    public static function getDb() {
        return \Yii::$app->db2;  // use the "db2" application component
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [[['tienteID'], 'safe']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tienteID' => 'Mã tiền tệ',
        ];
    }

}

?>