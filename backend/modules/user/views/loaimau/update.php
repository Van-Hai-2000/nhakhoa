<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LoaiMau */

$this->title = 'Cập nhật: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Loại mẫu', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="loai-mau-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success pull-right']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'factory' => $factory,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
