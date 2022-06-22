<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserAdminSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\widgets\ActiveForm;

$this->title = 'Phân quyền cho tài khoản: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý tài khoản quản trị', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$form = ActiveForm::begin([
    'id' => 'user-admin-form',
    'options' => [
        'class' => 'form-horizontal'
    ]
]);
?>
<div class="user-admin-index">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content auth_list">
                    <?php if (isset($rule)): $row = 1;
                        foreach ($rule as $key => $value): $cnt = count($value);
                            ?>
                            <div class="col-md-4">
                                <div class="auth_content">
                                    <h2 class="x_title"><?= $key ?></h2>
                                    <?php foreach ($value as $item): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="<?= $item['id'] ?>"
                                                   name="auth_item[]"
                                                   id="<?= $item['id'] ?>" <?= isset($pass_rule) && $pass_rule && in_array($item['id'], $pass_rule) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="<?= $item['id'] ?>">
                                                <?= $item['item'] ?>
                                            </label>
                                        </div>
                                        <?php $row++; endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<style>
    .auth_list {
        display: flex;
        flex-wrap: wrap;
    }

    .auth_content {
        padding: 10px 15px 10px 15px;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 1px 1px 10px 3px #f1f1f165;
    }

    .auth_list label {
        margin-left: 10px;
    }
</style>