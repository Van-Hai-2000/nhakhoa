<?php

use \common\components\ActiveFormC;
?>
<div class="news-category-form">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
            $form = ActiveFormC::begin1([
                'id' => 'user-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                    'title_form' => $this->title
                ]
            ]);
            ?>

            <?= $form->fieldB($model, 'name')->textInput()->label() ?>

            <?php
            if ($model->id != \common\models\shop\ShopTimeLimit::ID_NOT_LIMMIT) {
                $model->day = $model->time ? round($model->time / 24 / 60 / 60, 1) : '';
                echo $form->fieldB($model, 'day')->textInput()->label();
            }
            ?>

            <?= $form->fieldB($model, 'coin')->textInput()->label() ?>

            <?= $form->fieldB($model, 'status')->dropDownList([
                \common\components\ClaLid::STATUS_ACTIVED => 'Active',
                \common\components\ClaLid::STATUS_DEACTIVED => 'Hidden',
            ])->label() ?>

            <?php ActiveFormC::end1(['update' => $model->id]); ?>
        </div>
    </div>

</div>