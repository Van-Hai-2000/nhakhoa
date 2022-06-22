<?php

namespace common\models\affiliate;

use Yii;

/**
 * This is the model class for table "affiliate_click".
 *
 * @property string $id
 * @property string $affiliate_id ID affiliate
 * @property string $affiliate_user_id User người tạo affiliate
 * @property string $ipaddress
 * @property string $operating_system
 * @property string $created_at
 */
class AffiliateClick extends \yii\db\ActiveRecord
{

    const AFFILIATE_CLICK = 'affiliate_click_id';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'affiliate_click';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['affiliate_id', 'affiliate_user_id', 'created_at'], 'integer'],
            [['ipaddress'], 'string', 'max' => 50],
            [['operating_system'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'affiliate_id' => 'Affiliate ID',
            'affiliate_user_id' => 'Affiliate User ID',
            'ipaddress' => 'Ipaddress',
            'operating_system' => 'Operating System',
            'created_at' => 'Created Time',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function countClick($user_id, $options = [])
    {
        $condition = 'affiliate_user_id=:user_id';
        $params = [
            ':user_id' => $user_id
        ];
        if (isset($options['start_date']) && $options['start_date']) {
            $condition .= ' AND created_at >= :start_date';
            $start_date_string = $options['start_date'] . ' 00:00:00';
            $start_date = strtotime($start_date_string);
            $params[':start_date'] = $start_date;
        }
        if (isset($options['end_date']) && $options['end_date']) {
            $condition .= ' AND created_at <= :end_date';
            $end_date_string = $options['end_date'] . ' 23:59:59';
            $end_date = strtotime($end_date_string);
            $params[':end_date'] = $end_date;
        }
        $count = (new \yii\db\Query())->select('COUNT(*)')
            ->from('affiliate_click')
            ->where($condition, $params)
            ->scalar();
        return $count;
    }

    public static function getClick($user_id, $options = [])
    {
        $condition = 't.affiliate_user_id=:user_id';
        $params = [
            ':user_id' => $user_id
        ];
        if (isset($options['start_date']) && $options['start_date']) {
            $condition .= ' AND t.created_at >= :start_date';
            $start_date_string = $options['start_date'] . ' 00:00:00';
            $start_date = strtotime($start_date_string);
            $params[':start_date'] = $start_date;
        }
        if (isset($options['end_date']) && $options['end_date']) {
            $condition .= ' AND t.created_at <= :end_date';
            $end_date_string = $options['end_date'] . ' 23:59:59';
            $end_date = strtotime($end_date_string);
            $params[':end_date'] = $end_date;
        }
        $data = (new \yii\db\Query())
            ->select('t.*, r.campaign_source, r.aff_type, r.campaign_name')
            ->from('affiliate_click t')
            ->leftJoin('affiliate_link r', 'r.id = t.affiliate_id')
            ->where($condition, $params)
            ->all();
        return $data;
    }

    public static function getClickByAffiliateIds($idsArray)
    {
        $result = [];
        if (isset($idsArray) && $idsArray) {
            $data = AffiliateClick::find()->select('*')
                ->where('affiliate_id IN (' . join(',', $idsArray) . ')')
                ->asArray()
                ->all();
            if (isset($data) && $data) {
                foreach ($data as $item) {
                    $result[$item['affiliate_id']][] = $item;
                }
            }
        }
        return $result;
    }

    public static function countClickByAffiliateId($id)
    {
        $count = AffiliateClick::find()->select('*')
            ->where('affiliate_id=:affiliate_id', [
                ':affiliate_id' => $id
            ])->count();
        return isset($count) ? $count : 0;
    }

    public static function getModel($option) {
        $model = self::find()->where($option)->one();
        if($model) {
            if(strrpos($model->ipaddress,\common\components\ClaLid::getClientIp()) === false) {
                $model->ipaddress .=','.\common\components\ClaLid::getClientIp();
            }
            if(strrpos($model->operating_system,\common\components\ClaLid::getOS()) === false) {
                $model->operating_system .=','.\common\components\ClaLid::getOS();
            }
        }
        if(!$model) {
            $model = new self();
            foreach ($option as $key => $value) {
                $model->$key = $value;
            }
            $model->ipaddress = \common\components\ClaLid::getClientIp();
            $model->operating_system = \common\components\ClaLid::getOS();
            $model->created_at = time();
        }
        return $model;
    }

}
