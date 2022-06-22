<?php

namespace common\components;
define('DS', '/');

/**
 * @author hungtm <hungtm.0712@gmail.com>
 * get link host, host info
 */
class ClaHost
{

    /**
     * Get host upload for images and files
     */
    static function getUploadHost()
    {
        return __SERVER_NAME . '/static';
    }

    /**
     * get host view images
     */
    static function getImageHost()
    {
	//Yii::$app->cache->flush();
        $servername = \common\components\ClaSite::getServerName();
        return __SERVER_NAME . '/static';
    }

    /**
     * trả về dường dẫn tương đối của media
     */
    static function getMediaBasePath()
    {
        self::getImageHost();
    }

    public static function getLinkImage($path, $name, $options = [])
    {
        if (is_array($options)) {
            $size = isset($options['size']) ? $options['size'] . '/' : '';
        } else {
            $size = $options;
        }
        if ($name) {
            return self::getImageHost() . $path . $size . $name;
        }
        $type = isset($options['type']) ? $options['type'] . '/' : 'default/';
        return self::getImageHost() . '/media/images/default/'  . $type . $size . 'default.png';
    }
    static function formatImage($file = '', $width=0,$height=0) {
        if (!$width || !$height) {
            return '';
        }
        if ($file) {
            $path = explode(DS, $file);
            $file_name = $path[count($path) - 1];
            unset($path[count($path) - 1]);
            $dir = implode(DS, $path) . DS . 's' . $width . '_' . $height.DS;
            return $dir.$file_name;
        }

    }
}
