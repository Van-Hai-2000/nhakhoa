<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    // 'defaultRoute' => '/user/user/index',
    'language' => 'vi',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'controllerMap' => [
        'comments' => 'yii2mod\comments\controllers\ManageController',
        // Also you can override some controller properties in following way:
        'comments' => [
            'class' => 'yii2mod\comments\controllers\ManageController',
            'searchClass' => [
                'class' => 'yii2mod\comments\models\search\CommentSearch',
                'pageSize' => 25
            ],
        ],
    ],
    'bootstrap' => ['log'],
    'modules' => [
        'mail' => [
            'class' => 'backend\modules\mail\Mail',
        ],
        'rating' => [
            'class' => 'backend\modules\rating\Rating',
        ],
        'product' => [
            'class' => 'backend\modules\product\Product',
        ],
        'promotion' => [
            'class' => 'backend\modules\promotion\Promotion',
        ],
        'media' => [
            'class' => 'backend\modules\media\Media',
        ],
        'order' => [
            'class' => 'backend\modules\order\Order',
        ],
        'user' => [
            'class' => 'backend\modules\user\User',
        ],
        'news' => [
            'class' => 'backend\modules\news\News',
        ],
        'banner' => [
            'class' => 'backend\modules\banner\Banner',
        ],
        'recruitment' => [
            'class' => 'backend\modules\recruitment\Recruitment',
        ],
        'menu' => [
            'class' => 'backend\modules\menu\Menu',
        ],
        'chat' => [
            'class' => 'backend\modules\chat\Chat',
        ],
        'withdraw' => [
            'class' => 'backend\modules\withdraw\Withdraw',
        ],
        'gcacoin' => [
            'class' => 'backend\modules\gcacoin\Gcacoin',
        ],
        'auth' => [
            'class' => 'backend\modules\auth\Auth',
            'layout' => 'left-menu'
        ],
        'comment' => [
            'class' => 'yii2mod\comments\Module',
        ],
        'review' => [
            'class' => 'backend\modules\review\Review',
        ],
        'qa' => [
            'class' => 'backend\modules\qa\Qa',
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
        'notifications' => [
            'class' => 'backend\modules\notifications\Notifications',
        ],
        'affiliate' => [
            'class' => 'backend\modules\affiliate\Affiliate',
        ],
        'branch' => [
            'class' => 'backend\modules\branch\Branch',
        ],
        'service' => [
            'class' => 'backend\modules\service\Service',
        ],
        'sale' => [
            'class' => 'backend\modules\sale\Sale',
        ],
        'medicine' => [
            'class' => 'backend\modules\medicine\Medicine',
        ],
        'commission' => [
            'class' => 'backend\modules\commission\Commission',
        ],
        'thuchi' => [
            'class' => 'backend\modules\thuchi\Thuchi',
        ],
        'voucher' => [
            'class' => 'backend\modules\voucher\Voucher',
        ],
        'checklist' => [
            'class' => 'backend\modules\checklist\CheckList',
        ],
        'notify' => [
            'class' => 'backend\modules\notify\Notify',
        ],
        'hsba' => [
            'class' => 'backend\modules\hsba\Hsba',
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'yii2mod.comments' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/comments/messages',
                ],
            // ...
            ],
        ],
        'user' => [
            'identityClass' => 'backend\models\UserAdmin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                    [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCsrfValidation' => true,
            'class' => 'common\components\Request',
            'web' => '/backend/web',
            'adminUrl' => '/admin'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'urlManagerFrontEnd' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => ['position' => \yii\web\View::POS_HEAD],
                    'sourcePath' => null, // do not publish the bundle
                    'js' => [
                        '//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
    'as AccessBehavior' => [
        'class' => 'backend\components\AccessBehavior'
    ],
    'as access' => [
        'class' => 'backend\modules\auth\components\AccessControl',
        'allowActions' => [
            'site/*',
            'admin/*',
            'some-controller/some-action',
        // The actions listed here will be allowed to everyone including guests.
        // So, 'admin/*' should not appear here in the production, of course.
        // But in the earlier stages of your development, you may probably want to
        // add a lot of actions here until you finally completed setting up rbac,
        // otherwise you may not even take a first step.
        ]
    ],
];
