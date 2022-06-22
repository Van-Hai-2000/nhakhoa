<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\user\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách bệnh nhân';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="user-index">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?= Html::encode($this->title) ?></h2>
                        <?php if (\backend\modules\auth\components\Helper::checkRoute('/user/user/log')) { ?>
                            <!--                        <button type="button" class="btn btn-warning pull-right" data-toggle="modal"-->
                            <!--                                data-target=".log" onclick="load_log_user()"><i class="glyphicon glyphicon-film"></i> Xem lịch sử-->
                            <!--                        </button>-->
                            <?= Html::a('Xem lịch sử thay đổi', ['/user/user-log/index'], ['class' => 'btn btn-warning pull-right', 'target' => '_blank']) ?>
                        <?php } ?>
                        <?= Html::a('Import Excel', ['excel'], ['class' => 'btn btn-success pull-right']) ?>
                        <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'id',
                                    'options' => ['style' => 'width:130px']
                                ],
                                'username',
                                'phone',
                                'address',
                                [
                                    'attribute' => 'sex',
                                    'options' => ['style' => 'width:110px'],
                                    'value' => function ($modle) {
                                        $sex = \common\models\user\User::getSex();
                                        return $modle->sex ? $sex[$modle->sex] : '';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'sex', ['' => 'Chọn giới tính'] + \common\models\user\User::getSex(), ['class' => 'form-control'])
                                ],
                                [
                                    'attribute' => 'birthday',
                                    'options' => ['style' => 'width:130px'],
                                    'value' => function ($model) {
                                        return date('d-m-Y', $model->birthday);
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'value' => function ($modle) {
                                        $sex = \common\models\user\User::getSex();
                                        return $modle->status == 1 ? 'Kích hoạt' : 'Khóa';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel, 'status', ['' => 'Chọn'] + \common\models\user\User::getStatus(), ['class' => 'form-control'])
                                ],
                                [
                                    'attribute' => 'admin_id',
                                    'value' => 'userAdmin.fullname'
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'options' => ['style' => 'width:100px']
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Lịch sử log-->
<?= $this->render('layouts/log/log'); ?>