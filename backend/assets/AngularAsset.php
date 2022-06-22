<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AngularAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/angularjs-1.8.2/ui-bootstrap/ui-bootstrap-custom-2.5.0-csp.css',
        'js/select2/dist/css/select2.min.css',
        'js/angularjs-1.8.2/ui-select/dist/select.min.css',
        'js/chartjs/chart.min.css'
    ];
    public $js = [
        'js/angularjs-1.8.2/angular.min.js',
        'js/angularjs-1.8.2/angular-animate.min.js',
        'js/angularjs-1.8.2/angular-touch.min.js',
        'js/angularjs-1.8.2/ui-bootstrap/ui-bootstrap-custom-tpls-2.5.0.min.js',
        'js/angularjs-1.8.2/ui-sortable-0.19.0/sortable.min.js',
        'js/angularjs-1.8.2/ui-uploader/dist/uploader.min.js',
        'js/select2/dist/js/select2.min.js',
        'js/angularjs-1.8.2/angular-sanitize.min.js',
        'js/angularjs-1.8.2/ui-select/dist/select.min.js',
        'js/chartjs/chart.min.js',
        'js/angularjs-1.8.2/angular-chart.min.js',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

}
