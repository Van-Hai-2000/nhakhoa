<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use common\models\menu\Menu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MenuGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý nhóm menu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-group-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo nhóm menu', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php
                    $groups = $dataProvider->getModels();
                    $count = $dataProvider->getCount();
                    if ($dataProvider->getCount()) {
                        foreach ($groups as $group) {
                            ?>
                            <div class="col-md-6 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><?php echo $group->name ?></h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li>
                                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                            </li>
                                            <li>
                                                <a href="<?= Url::to(['menu-group/update', 'id' => $group->id]); ?>"><i class="fa fa-wrench"></i></a>
                                            </li>
                                            <li>
                                                <a onclick="return confirm('Bạn có chắc là sẽ xóa mục này không?')" href="<?= Url::to(['menu-group/delete', 'id' => $group->id]) ?>"><i class="fa fa-close"></i></a>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <p>
                                            <?= Html::a('Thêm menu', ['menu/create', 'gid' => $group->id], ['class' => 'btn btn-success pull-right btn-sm']) ?>
                                        </p>
                                        <!--CONTENT-->
                                        <?php
                                        $model = new Menu();
                                        $dataProviderMenu = new ArrayDataProvider([
                                            'allModels' => $model->getDataProvider(0, 0, $group->id),
                                            'sort' => [
                                                'attributes' => ['id', 'name', 'status'],
                                            ],
                                            'pagination' => [
                                                'pageSize' => 100,
                                            ],
                                        ]);
                                        ?>
                                        <?=
                                        GridView::widget([
                                            'dataProvider' => $dataProviderMenu,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                'name' => [
                                                    'header' => 'Tên menu',
                                                    'content' => function($model) {
                                                        return $model['name'];
                                                    }
                                                ],
                                                'order' => [
                                                    'header' => 'Thứ tự',
                                                    'content' => function($model) {
                                                        return $model['order'];
                                                    }
                                                ],
                                                'status' => [
                                                    'header' => 'Trạng thái',
                                                    'content' => function($model) {
                                                        return $model['status'] ? 'Hiển thị' : 'Ẩn';
                                                    }
                                                ],
                                                [
                                                    'class' => 'yii\grid\ActionColumn',
                                                    'template' => '{update} {delete}',
                                                    'urlCreator' => function ($action, $model, $key, $index) {
                                                        if ($action === 'update') {
                                                            $url = Url::to(['menu/update', 'id' => $model['id']]);
                                                            return $url;
                                                        } else if($action === 'delete') {
                                                            $url = Url::to(['menu/delete', 'id' => $model['id']]);
                                                            return $url;
                                                        }
                                                    }
                                                ],
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
