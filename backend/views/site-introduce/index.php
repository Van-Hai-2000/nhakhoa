<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SiteIntroduce */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Site Introduces';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-introduce-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Site Introduce', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'title_en',
            'short_description:ntext',
            'short_description_en:ntext',
            // 'description:ntext',
            // 'description_en:ntext',
            // 'avatar_path',
            // 'avatar_name',
            // 'link',
            // 'embed',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
