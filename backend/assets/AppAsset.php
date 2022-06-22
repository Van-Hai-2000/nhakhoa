<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/custom.css',
        'gentelella/font-awesome/css/font-awesome.min.css',
        'gentelella/nprogress/nprogress.css',
        'gentelella/select2/dist/css/select2.min.css',
        'gentelella/bootstrap-daterangepicker/daterangepicker.css',
        'gentelella/iCheck/skins/flat/green.css',
        'gentelella/switchery/dist/switchery.min.css',
        'gentelella/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css',
        'gentelella/build/css/custom.min.css',
        'js/jqueryui/jquery-ui.min.css',
    ];
    public $js = [
        'gentelella/bootstrap/dist/js/bootstrap.min.js',
//        'gentelella/nprogress/nprogress.js',
        'gentelella/select2/dist/js/select2.full.min.js',
        'gentelella/moment/min/moment.min.js',
        'gentelella/bootstrap-daterangepicker/daterangepicker.js',
        'gentelella/iCheck/icheck.min.js',
        'gentelella/devbridge-autocomplete/dist/jquery.autocomplete.min.js',
        'gentelella/jquery.tagsinput/src/jquery.tagsinput.js',
        'gentelella/switchery/dist/switchery.min.js',
        'gentelella/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js',
        'gentelella/build/js/custom.min.js',
        'js/jqueryui/jquery-ui.min.js',
        'js/add.js',
        'js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\AngularAsset'
    ];
}
