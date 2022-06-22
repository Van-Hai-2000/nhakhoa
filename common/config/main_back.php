<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=db_gca',
            'username' => 'sql_gca',
            'password' => 'N64RP1Vkeg3m-EveoWWMjLXwW',
            'charset' => 'utf8',
        ],
        'cache' => [
            'useMemcached' => true,
            'keyPrefix' => 'gca',
            'class' => 'yii\caching\MemCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],

//
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
//                    'sourceLanguage' => 'vi',
//                    'fileMap' => [
//                        'app' => 'app.php',
//                        'news' => 'news.php',
//                        'product' => 'product.php',
//                    ],
                ],
            ],
        ],
    ],
];
