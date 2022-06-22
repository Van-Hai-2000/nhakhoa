<?php

namespace common\components;

class ClaUrl extends \yii\helpers\Url
{

    static function to($url = '', $scheme = false)
    {
        if (\Yii::$app->id != 'app-frontend') {
            if (is_array($url)) {
                $options[] = '/site/router-url';
                foreach ($url as $key => $value) {
                    $options[$key ? $key : 'url'] = $value;
                }
            } else {
                $options = ['/site/router-url', 'url' => $url];
            }
            return \yii\helpers\Url::to(\Yii::$app->urlManagerFrontEnd->createUrl($options));
        }
        return parent::to($url, $scheme);
    }
}
