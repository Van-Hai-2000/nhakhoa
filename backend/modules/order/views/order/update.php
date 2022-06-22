<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\order\Order;
use common\components\ClaHost;

/* @var $this yii\web\View */
/* @var $model common\models\order\Order */

$this->title = 'Cập nhật đơn hàng: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý đơn hàng', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="order-update">

    <div class="order-form">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'order-form',
                            'options' => [
                                'class' => 'form-horizontal'
                            ]
                ]);
                ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">


                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">
                                        Thông tin đơn hàng
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#tab_content2" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">
                                        In đơn hàng
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#tab_content3" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">
                                        Logs
                                    </a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <?=
                                    $this->render('partial/basicinfo', [
                                        'form' => $form,
                                        'model' => $model,
                                        'products' => $products,
                                        // 'money' => $money,
                                        // 'user_log_money' => $user_log_money,
                                        // 'logs' => $logs
                                    ]);
                                    ?>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="home-tab">
                                    <?=
                                    $this->render('partial/printorder', [
                                        'form' => $form,
                                        'model' => $model,
                                        'products' => $products,
                                        // 'money' => $money,
                                        // 'user_log_money' => $user_log_money,
                                    ]);
                                    ?>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="home-tab">
                                    <?=
                                    $this->render('partial/logs', [
                                        'model' => $model
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>