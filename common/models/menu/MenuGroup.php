<?php

namespace common\models\menu;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "menu_group".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class MenuGroup extends \common\models\ActiveRecordC {

    const MENU_5_REASON = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'menu_group';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'description'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Tên nhóm',
            'description' => 'Mô tả nhóm',
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
    
    /**
     * @hungtm
     * return array options banner group
     */
    public static function optionsMenuGroup() {
        $data = (new Query())->select('*')
                ->from('menu_group')
                ->all();
        $promt = array(0 => ' --- Chọn nhóm menu --- ');
        return $promt + array_column($data, 'name', 'id');
    }

}
