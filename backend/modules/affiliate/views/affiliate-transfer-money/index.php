<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\affiliate\search\AffiliateTransferMoneySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách yêu cầu rút tiền';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="affiliate-transfer-money-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                            'user_id' => [
                                'header' => 'Người yêu cầu',
                                'value' => function($model) {
                                    $user = common\models\User::findOne($model->user_id);
                                    return $user['username'];
                                }
                            ],
                            'money' => [
                                'header' => 'Số tiền yêu cầu',
                                'value' => function($model) {
                                    return number_format($model->money, 0, ',', '.');
                                }
                            ],
                            'card_bank' => [
                                'header' => 'Thông tin nhận tiền',
                                'content' => function($model) {
                                    $paymentInfo = \common\models\affiliate\AffiliatePaymentInfo::findOne($model->user_id);
                                    return isset($paymentInfo->payment_info) && $paymentInfo->payment_info ? $paymentInfo->payment_info : '';
                                }
                            ],
                            'note',
                            'status' => [
                                'header' => 'Trạng thái',
                                'value' => function($model) {
                                    return common\models\affiliate\AffiliateTransferMoney::getNameStatus($model->status);
                                }
                            ],
                                [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}'
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
