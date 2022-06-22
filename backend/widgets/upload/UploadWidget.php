<?php

namespace backend\widgets\upload;

use Yii;
use yii\base\Widget;
use common\components\ClaGenerate;

class UploadWidget extends Widget {

    public $boxId = '';
    public $type = 'images'; // Loại upload image hay file
    public $id = 'uploadfile'; // ID cho nút upload
    public $name = 'files';
    public $path = array('img');
    public $key = 'web3nhatpro';
    public $limit = 20; // Giới hạn số file có thể upload lên
    public $style = '';  // style for upload
    public $imageoptions = array();
    //
    public $loadScript = true;
    public $buttonwidth = 100;
    public $buttonheight = 30;
    public $buttontext = 'Select File';
    public $buttonClass = 'btn btn-success';
    public $buttonStyle = 'style1';
    public $multi = true;
    public $displayvaluebox = true;
    public $fileSizeLimit = 10485760; // interger bytes; 10Mb
    public $uploader = '';
    protected $application = '';
    public $oncecomplete = '';
    public $queuecomplete = '';
    public $onUploadStart = '';

    public function init() {
        if (!$this->uploader) {
            $this->uploader = \yii\helpers\Url::to(['/media/upload/uploadimage']);
        }
        $this->application = Yii::$app->id;
        if (is_string($this->fileSizeLimit)) {
            $this->fileSizeLimit = (int) $this->fileSizeLimit * 1024 * 1000;
        }
        if (!$this->boxId) {
            $this->boxId = 'uploadBox' . ClaGenerate::getUniqueCode();
        }
        parent::init();
    }

    public function run() {
        return $this->render($this->type . $this->style, array(
                    'boxId' => $this->boxId,
                    'type' => $this->type,
                    'stype' => $this->type,
                    'id' => $this->id,
                    'name' => $this->name,
                    'key' => $this->key,
                    'limit' => $this->limit,
                    'buttonwidth' => $this->buttonwidth,
                    'buttonheight' => $this->buttonheight,
                    'buttonStyle' => $this->buttonStyle,
                    'multi' => $this->multi,
                    'buttontext' => $this->buttontext,
                    'displayvaluebox' => $this->displayvaluebox,
                    'fileSizeLimit' => $this->fileSizeLimit,
                    'uploader' => $this->uploader,
                    'application' => $this->application,
                    'oncecomplete' => $this->oncecomplete,
                    'queuecomplete' => $this->queuecomplete,
                    'buttonClass' => $this->buttonClass,
                    'path' => $this->path,
                    'imageoptions' => $this->imageoptions
        ));
    }

}

?>