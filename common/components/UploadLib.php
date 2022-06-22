<?php

namespace common\components;

/**
 * @author: hungtm <hungtm.0712@gmail.com>
 * class upload file by Curl
 *
 * 
 * ===============================================================
 * Option for upload
 * ===============================================================
 * # type for upload
 * int upload_type:
 *  + 1: upload only images
 *  + 2: upload any files
 * 
 * # allowed extensions to upload
 * array extension
 * ex: array('img','jpg')
 * 
 * # create image thumb if file upload is image
 * boolen create_thumb
 *  + true: create image thumb
 *  + false: not create image thumb
 * 
 * # path to upload
 * array path
 * ex: array('images','top1')
 * 
 * # max size to upload
 * int max_size
 * ex: max_size = 100000
 * System doest allow set max_size over 500000
 * 
 * # max width
 * int max_width max width of image file upload
 * 
 * # max height
 * int max_height max height of image file upload
 * 
 * # array resize
 * array resize 
 * ex array(
 *  array(10,20),
 *  array(30,500),
 *  array(10,20,10,20)
 * )
 * if array(10,20) system will create thumb with width is 10, height is 20
 * if array(10,20,10,20) system will create thumb with crop x1=10,x2=20,y1=10,y2=20
 * 
 * =============================================================================
 * Example:
 * <input name="upload" type="file"/>
 * upload:
 *  $upload = new UploadLib($_FILE["upload"])
 *  // set path
 *  $upload->setPath(array('as','bc'));
 *  
 *  $upload->uploadImage();
 * 
 */
class UploadLib {

    /**
     * config host
     */
    public $host;

    /**
     * const for upload
     */
    const UPLOAD_IMAGE = 1;
    const UPLOAD_FILES = 2;
    const SIZE_MAX_IMAGE = 10;

    /**
     * CURL init
     */
    protected $cUrl;

    /**
     * @var type 
     */
    protected $upload_type;

    /**
     *
     * @var type 
     */
    protected $extension = array();

    /**
     *
     * @var type 
     */
    protected $create_thumb = true;

    /**
     *
     * @var type 
     */
    protected $path = array();

    /**
     *
     * @var type 
     */
    protected $max_size;

    /**
     *
     * @var type 
     */
    protected $max_width;

    /**
     *
     * @var type 
     */
    protected $max_height;

    /**
     *
     * @var type 
     */
    protected $resize;

    /**
     *
     * @var type 
     */
    protected $forceResize;

    /**
     * respon from serve
     * @var array
     */
    protected $allResponse;

    /**
     * status of reponse
     * @var int
     */
    protected $status;

    /**
     * only content from resonse result
     * @var array
     */
    protected $response;

    /**
     * add array files to upload
     * 
     * @var ArrayAccess
     */
    public $files;

    /**
     *
     * @var type 
     */
    public $key = 'w3nhatpro';

    /**
     * getter and setter
     */
    public function getUploadtype() {
        return $this->upload_type;
    }

    public function getExtension() {
        return $this->extension;
    }

    public function getCreatethumb() {
        return $this->create_thumb;
    }

    public function getPath() {
        return $this->path;
    }

    public function getMaxsize() {
        return $this->max_size;
    }

    public function getMaxwidth() {
        return $this->max_width;
    }

    public function getMaxheight() {
        return $this->max_height;
    }

    public function getResize() {
        return $this->resize;
    }

    public function getResource() {
        return $this->resource;
    }

    public function setUpload_type($upload_type) {
        $this->upload_type = $upload_type;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function setCreate_thumb($create_thumb) {
        $this->create_thumb = $create_thumb;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function setMaxsize($max_size) {
        $this->max_size = $max_size;
    }

    public function setMaxwidth($max_width) {
        $this->max_width = $max_width;
    }

    public function setMaxheight($max_height) {
        $this->max_height = $max_height;
    }

    public function setResize($resize) {
        $this->resize = $resize;
    }

    /**
     * $size = array(0=>width, 1=>height);
     * @param type $size
     */
    public function setForceSize($size) {
        $this->forceResize = $size;
    }

    public function getFiles() {
        return $this->files;
    }

    public function setFiles($files) {
        $this->files = $files;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getStatus() {
        return $this->status;
    }

    /**
     * build data to sent
     * using get_object_var to get value;
     * only get protected properties
     * 
     * @return array
     */
    public function prepareData() {
        /* check if files is not set, return false */
        if (count($this->getFiles()) == 0) {
            return false;
        }
        /* array to unset some properies */
        $arrayUnset = array(
            'host', 'cUrl', 'files', 'response', 'allResponse', 'status'
        );

        $p = get_object_vars($this);
        foreach ($arrayUnset as $item) {
            unset($p[$item]);
        }
        $file = $this->getFiles();
        $param['options'] = json_encode($p);
        $param['name'] = $file['name'];
        if (class_exists('CURLFile', false)) {
            $param['tmp_name'] = new \CURLFile($file['tmp_name'], $file['type'], $file['name']); //'@' . $this->getFiles()['tmp_name'];
        } else
            $param['tmp_name'] = "@" . $file['tmp_name']; //'@' . $this->getFiles()['tmp_name'];
        return $param;
    }

    /**
     * constructor
     * set FILE that is uploaded when create intance of this class
     */
    public function __construct($file = null) {
        if ($file) {
            $this->setFiles($file);
        }
        $this->cUrl = curl_init();
        $this->createPathBySite();
        # host upload
        $this->host = ClaHost::getUploadHost() . '/index.php';
    }

    /**
     * Closes a curl session
     */
    public function __destruct() {
        // close our curl session
        if ($this->cUrl) {
            curl_close($this->cUrl);
        }
    }

    /**
     * start upload file by curl
     * 
     * firstly, get data from prepareData method
     * check data is valid or not
     * create CURL
     * set response to response propety
     * 
     * @return UploadLib $this
     */
    public function upload() {
        /* set path default follow user_id */
        if (!$this->path) {
            $this->createPathBySite();
        }
        // set curl POST options
        $curl = $this->cUrl;
        /* check data is true or not */
        $data = $this->prepareData();
        if (!$data) {
            return false;
        }
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        $responseBody = curl_exec($curl);
        $this->allResponse = $responseBody;
        $this->processResponse();
        return $this;
    }

    /**
     * this method is used for upload images
     * set upload_type is const UPLOAD_IMAGE
     * 
     * @return upload()
     */
    public function uploadImage() {
        $this->upload_type = self::UPLOAD_IMAGE;
        return $this->upload();
    }

    /**
     * this method is used for upload files
     * set upload_type is const UPLOAD_FILES
     * 
     * @return upload()
     */
    public function uploadFile() {
        $this->upload_type = self::UPLOAD_FILES;
        return $this->upload();
    }

    /**
     * get response from server
     * json_decode this response property
     * @param boolen $j : true: return json_decode
     *                    false: return json string
     * @return array
     */
    public function getAllResponse($decode = true) {
        if ($decode) {
            return json_decode($this->allResponse, true);
        } else {
            return $this->allResponse;
        }
    }

    /**
     * create path to upload follow user_id
     * upload with structure folder
     *  user_id/filename
     * @param $user_id
     * @return UploadLib $this
     */
    public function createPathFlUser($user_id = NULL) {
        $user_id = !$user_id ? Yii::app()->user->id : $user_id;
        $this->path = array('user', $user_id);
        return $this;
    }

    public function createPathBySite($site_id = NULL) {
        $this->path = array(date('m-Y'));
        return $this;
    }

    /**
     * process reponse result
     * assign to some properties
     * @return void
     */
    public function processResponse() {
        $result = $this->getAllResponse();
        $this->status = isset($result['status']) ? intval($result['status']) : NULL;
        $this->response = isset($result['response']) && is_array($result['response']) ? $result['response'] : array();
    }

    /**
     * download file
     * @param type $options
     */
    public function download($options = array()) {
        $filetype = isset($options['extension']) ? $options['extension'] : "";
        $filepath = isset($options['path']) ? $options['path'] : "";
        $filename = isset($options['name']) ? $options['name'] : "";
        $filerealname = isset($options['realname']) ? $options['realname'] : "";
        if ($filetype && $filepath && $filename) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_URL, ClaHost::getUploadHost() . "/download.php");
            $post = array(
                "action" => "downloadfile",
                "key" => $this->key,
                "path" => $filepath,
                "name" => $filename,
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response = curl_exec($ch);
            if ($response) {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Type: $filetype");
                header("Content-Disposition: attachment; filename=$filerealname");
                header("Content-Transfer-Encoding: binary");
                //header("Content-Length: " . $filesize);
                echo $response;
            }
        }
    }

    /**
     * download file
     * @param type $options
     */
    public function deletefile($options = array()) {
        $filepath = isset($options['path']) ? $options['path'] : "";
        $filename = isset($options['name']) ? $options['name'] : "";
        if ($filepath && $filename) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_URL, ClaHost::getUploadHost() . "/deletefile.php");
            $post = array(
                "action" => "deletefile",
                "key" => $this->key,
                "path" => $filepath,
                "name" => $filename,
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response = curl_exec($ch);
            if ($response == 'done')
                return true;
            return false;
        }
    }

    /**
     * get file and save to media server from a url link
     * @param type $options
     */
    public function getFile($options = array()) {
        $filelink = isset($options['link']) ? $options['link'] : "";
        $filepath = $this->getPath();
        $filetype = isset($options['filetype']) ? $options['filetype'] : "";
        if ($filelink && $filepath && $filetype) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
            curl_setopt($ch, CURLOPT_URL, ClaHost::getUploadHost() . "/getFile.php");
            $post = array(
                "action" => "getfile",
                "key" => $this->key,
                "path" => $filepath,
                "link" => $filelink,
                'filetype' => $filetype
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            $response = curl_exec($ch);
            $this->allResponse = $response;
            $this->processResponse();
        }
    }

    static function getSaveLink($options, $path = '')
    {
        $url_base = '../../static';
        $file_url_rv = $options['baseUrl'];
        $file_url_to = '/media/images/' . $path;
        if (!is_dir($url_base . $file_url_to)) {
            mkdir($url_base . $file_url_to);
        }
        $re_name = time() . '_' . $options['name'];
        rename($url_base . $file_url_rv . $options['name'], $url_base . $file_url_to . $re_name);
        $options['baseUrl'] = $file_url_to;
        $options['name'] = $re_name;
        return $options;
    }
    static function getUnLink($path)
    {
        $path = str_replace(\common\components\ClaHost::getImageHost(), '../../static', $path);
        @unlink($path);
    }
}
