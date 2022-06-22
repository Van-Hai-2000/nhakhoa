<?php

namespace common\models\user;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use common\components\ClaLid;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user_device}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $device_id
 * @property integer $type
 * @property integer $created_time
 * @property integer $modified_time
 */
class UserDevice extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_device}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'device_id'], 'required'],
            [['user_id', 'type', 'created_time', 'modified_time'], 'integer'],
            [['device_id'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'device_id' => 'Device ID',
            'type' => 'Type',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
        ];
    }

    function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_time = $this->modified_time = time();
        } else {
            $this->modified_time = time();
        }
        return parent::beforeSave($insert);
    }

    /**
     * update device for user
     * @param type $user_id
     */
    static function updateDevice($user_id = 0, $device_id = 0, $type = 0)
    {
        if (!$user_id && !Yii::$app->user->isGuest) {
            $user_id = Yii::$app->user->id;
        }
        if (!$user_id || !$device_id) {
            return false;
        }
        $device = self::find()->where(['device_id' => $device_id])->one();
        if ($device) {
            if ($device->user_id != $user_id) {
                $device->user_id = $user_id;
                $device->save();
            }
        } else {
            $model = new \common\models\user\UserDevice();
            $model->device_id = $device_id;
            $model->user_id = $user_id;
            $model->type = $type;
            $model->save();
        }
        return true;
    }

    /**
     * Get user_device
     * @param type $options
     * @return array
     */
    public static function getUserDevices($options = array(), $countOnly = false)
    {
        //
        $condition = '1=1';
        $params = array();
        $command = new \yii\db\Query();
        $select = 't.*';
        // add more condition
        if (isset($options['condition']) && $options['condition']) {
            $condition .= ' AND ' . $options['condition'];
        }
        if (isset($options['params'])) {
            $params = array_merge($params, $options['params']);
        }
        // filter by user
        if (isset($options['user_id']) && $options['user_id']) {
            $command->andWhere(['user_id' => $options['user_id']]);
        } else {
            $command->andWhere(['user_id' => -11]);
        }
        // filter by user
        if (isset($options['device_id']) && $options['device_id']) {
            $condition .= " AND t.device_id=:device_id";
            $params[':device_id'] = $options['device_id'];
        }
        // filter by type
        if (isset($options['type']) && $options['type']) {
            $condition .= " AND t.type=:type";
            $params[':type'] = $options['type'];
        }

        // count only
        if ($countOnly) {
            $select = 'count(*)';
            $count = $command->select($select)
                ->from('user_device' . ' t')
                ->where($condition, $params)
                ->count();
            return $count;
        }
        //order
        $order = 't.created_time DESC';
        if (isset($options['order']) && $options['order']) {
            $order = $options['order'];
        }
        if (isset($options['_order']) && $options['_order']) {
            $order = false;
        }
        if ($order) {
            $command->orderBy($order);
        }
        //
        if (isset($options['limit']) && (int) $options['limit']) {
            $command->limit((int) $options['limit']);
            $options[ClaLid::PAGE_VAR] = isset($options[ClaLid::PAGE_VAR]) ? $options[ClaLid::PAGE_VAR] : 1;
            $offset = ($options[ClaLid::PAGE_VAR] - 1) * $options['limit'];
            $command->offset($offset);
        }
        //
        $user_device = $command->select($select)
            ->from('user_device' . ' t')
            ->where($condition, $params)
            ->all();

        $results = array();
        $devices = array();
        foreach ($user_device as $p) {
            $results[$p['id']] = $p;
            $devices[] = $p['device_id'];
        }
        if (isset($options['getDevices']) && $options['getDevices']) {
            return $devices;
        }
        return $results;
    }

    static function getModel($options)
    {
        $model = self::findOne($options);
        if (!$model) {
            $model = new self();
            $model->attributes = $options;
            $model->type = 0;
        }
        return $model;
    }
}
