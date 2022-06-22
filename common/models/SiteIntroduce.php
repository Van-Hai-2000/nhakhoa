<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_introduce".
 *
 * @property string $id
 * @property string $title
 * @property string $title_en
 * @property string $short_description
 * @property string $short_description_en
 * @property string $description
 * @property string $description_en
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $link
 * @property string $embed
 * @property string $created_at
 * @property string $footer_notice
 * @property string $contact_notice
 * @property string $map
 * @property string $footer_notice_en
 * @property string $contact_notice_en
 */
class SiteIntroduce extends \yii\db\ActiveRecord {
    
    public $avatar = '';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'site_introduce';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_description', 'description'], 'required'],
            [['short_description', 'short_description_en', 'description', 'description_en'], 'string'],
            [['created_at'], 'integer'],
            [['title', 'title_en', 'avatar_path', 'avatar_name', 'link', 'embed', 'map'], 'string', 'max' => 255],
            [['avatar', 'footer_notice', 'contact_notice', 'title_en', 'short_description_en', 'description_en', 'map',
                'footer_notice_en', 'contact_notice_en'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Tiêu đề liên hệ',
            'title_en' => 'Title contact',
            'short_description' => 'Mô tả ngắn liên hệ',
            'short_description_en' => 'Short description contact',
            'description' => 'Trang giới thiệu',
            'description_en' => 'Content about us',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'link' => 'Link video',
            'embed' => 'Embed',
            'created_at' => 'Created At',
            'map' => 'Created At',
            'contact_notice_en' => 'Thông tin trang liên hệ (Tiếng anh)',
            'footer_notice_en' => 'Thông tin chân trang (Tiếng anh)',
            'contact_notice' => 'Thông tin trang liên hệ',
            'footer_notice' => 'Thông tin chân trang',
            'avatar' => Yii::t('app', 'image_contact')
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

}
