<?php

namespace backend\modules\review;

/**
 * CustomerReview module definition class
 */
class Review extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\review\controllers';
    public $defaultRoute = 'customer-reviews/index';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
