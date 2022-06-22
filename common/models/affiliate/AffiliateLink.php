<?php

namespace common\models\affiliate;

use Yii;

/**
 * This is the model class for table "affiliate_link".
 *
 * @property string $id
 * @property string $user_id
 * @property string $url
 * @property string $link
 * @property string $link_short
 * @property string $campaign_source
 * @property string $aff_type
 * @property string $campaign_name
 * @property string $campaign_content
 * @property integer $type
 * @property string $object_id
 * @property string $updated_at
 * @property string $created_at
 */
class AffiliateLink extends \yii\db\ActiveRecord
{

    const TYPE_PRODUCT = 1;
    const TYPE_CATEGORY = 2;
    const AFFILIATE_NAME = 'affiliate_id';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'affiliate_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'object_id', 'updated_at', 'created_at'], 'integer'],
            [['url'], 'required'],
            [['url', 'link', 'link_short', 'campaign_source', 'aff_type', 'campaign_name', 'campaign_content'], 'string', 'max' => 255],
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
            'url' => 'Url',
            'link' => 'Link',
            'link_short' => 'Link Short',
            'campaign_source' => 'Campaign Source',
            'aff_type' => 'Aff Type',
            'campaign_name' => 'Campaign Name',
            'campaign_content' => 'Campaign Content',
            'type' => 'Type',
            'object_id' => 'Object ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public static function getAllLink($user_id = null)
    {
        $user_id = $user_id ? $user_id : \Yii::$app->user->id;
        if ($user_id === NULL) {
            return [];
        }
        $data = AffiliateLink::find()
            ->where('user_id=:user_id AND type = 1', [':user_id' => $user_id])
            ->orderBy('id DESC')
            ->asArray()
            ->all();
        //
        $results = [];
        //
        if (isset($data) && $data) {
            foreach ($data as $item) {
                $results[$item['object_id']] = $item;
            }
        }
        return $results;
    }

    function beforeSave($insert)
    {
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

    function afterSave($insert, $changedAttributes)
    {
        if (!$this->link) {
            $this->link = $this->url . '?affiliate_id=' . $this->id;
            $this->save();
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    function isHas()
    {
        return self::findOne(['user_id' => $this->user_id, 'object_id' => $this->object_id, 'type' => $this->type]);
    }
}
