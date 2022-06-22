<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\shop\Shop;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý ngân hàng';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <?= Html::a('Xuất exel', ['/exel/exel', 'type' => 'USER'], ['class' => 'btn btn-success pull-right', 'target' => '_blank']) ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                   <pre>
                   <?php print_r($data);
                    ?>
                   </pre>
                </div>
            </div>
        </div>
    </div>
</div>