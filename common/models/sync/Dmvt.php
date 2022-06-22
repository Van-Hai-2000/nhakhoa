<?php

namespace common\models\sync;

use common\components\ClaLid;
use Yii;
use yii\db\Query;

class Dmvt extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dmvt';
    }

    public static function getDb() {
        return \Yii::$app->db2;  // use the "db2" application component
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [[['VtID', 'code', 'VtName', 'VtName2', 'FK_Nhvt2ID', 'FK_DvtId'], 'safe']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'VtID' => 'Mã sản phẩm',
        ];
    }

}

?>