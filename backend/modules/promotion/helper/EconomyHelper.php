<?php

class EconomyHelper extends CComponent {

    public static $_helper;

    public static function helper($isNew = false) {
        if (!is_null(self::$_helper) && !$isNew)
            return self::$_helper;
        else {
            $className = __CLASS__;
            $helper = self::$_helper = new $className();
            return $helper;
        }
    }

    public function loadMessage() {
        $reVal = '';
        if (Yii::app()->user->hasFlash('success')) {
            $reVal.= '<div class="alert alert-block alert-success">';
            $reVal.= '<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>';
            $reVal.= '<p>' . Yii::app()->user->getFlash("success") . '</p>';
            $reVal.= '</div>';
        }
        if (Yii::app()->user->hasFlash('error')) {
            $reVal.= '<div class="alert alert-block alert-danger">';
            $reVal.= '<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>';
            $reVal.= '<p>' . Yii::app()->user->getFlash("error") . '</p>';
            $reVal.= '</div>';
        }
        echo $reVal;
    }

    public function removeString($string, $arrRemove, $sp = "") {
        if (!empty($arrRemove) && count($arrRemove)) {
            $string = str_replace($arrRemove, $sp, trim($string));
        }
        return trim($string);
    }

    public function removeJavascript($java) {
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $java);
        return $html;
    }

    public function stripSingle($tag, $string) {
        $string = preg_replace('/<' . $tag . '[^>]*>/i', '', $string);
        $string = preg_replace('/<\/' . $tag . '>/i', '', $string);
        return $string;
    }

    public function allowExtensions() {
        return array(
            'image/jpeg' => 'image/jpeg',
            'image/gif' => 'image/gif',
            'image/png' => 'image/png',
            'image/bmp' => 'image/bmp',
            'application/x-shockwave-flash' => 'application/x-shockwave-flash',
        );
    }



}

?>
