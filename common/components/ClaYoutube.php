<?php

namespace common\components;

/**
 * @description Class to get info of youtube link
 */
class ClaYoutube {

    protected $url = '';
    protected $_constructed = false;
    public $isLink = false;

    public function __construct($url = null) {
        if (isset($url)) {
            $this->url = $url;
            $this->_constructed = true;
        }
        $this->isLink = $this->isYoutubeLink();
    }

    function setUrl($url = '') {
        $this->url = $url;
        $this->isLink = $this->isYoutubeLink();
    }

    /**
     * return info of youtube linh
     * @return string|array
     */
    function getEmebed() {
        $url = $this->url;
        $return = array();
        if (!$url || !$this->isLink)
            return $return;
        $ourl = $url;
        $url = urlencode($url);
        $yturl = 'https://www.youtube.com/oembed?url=' . $url . '&format=json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $yturl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $_re = curl_exec($ch);
        curl_close($ch);
        $_re = json_decode($_re, true);
        if ($_re) {
            $return['width'] = $_re['width'];
            $return['height'] = $_re['height'];
            $return['title'] = $_re['title'];
            $return['thumbnail_url'] = $_re['thumbnail_url'];
            $return['embed_link'] = 'https://www.youtube.com/embed/' . $this->getYoutubeId($ourl);
        }
        return $return;
    }

    /**
     * get Youtube id
     * @param type $url
     * @return int
     */
    function getYoutubeId() {
        $url = $this->url;
        if (!$url || !$this->isLink)
            return 0;
        parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
        if ($my_array_of_vars['v'])
            return $my_array_of_vars['v'];

        return 0;
    }

    public function getYoutubeHost() {
        return array(
            'www.youtube.com',
            'youtube.com',
        );
    }

    /**
     * check is youtube url
     */
    function isYoutubeLink() {
        if (!$this->url)
            return false;
        $parseurl = parse_url($this->url);
        // var_dump($parseurl); die()
        if (isset($parseurl['host']) && in_array($parseurl['host'], $this->getYoutubeHost()))
            return true;
        return false;
    }

}
