<?php

use yii\helpers\Html;
use \common\components\ActiveFormC;

?>

<div class="user-form">
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

            <?=
                $form->fieldB($model, 'status')->dropDownList([
                    1 => 'Kích hoạt',
                    0 => 'Ngừng kích hoạt',
                ])->label()
            ?>

            <?= $form->fieldB($model, 'percent')->textInput()->label() ?>

            <?= $form->fieldB($model, 'time_start')->textDate(['format' => "DD-MM-YYYY HH:mm"])->label() ?>

            <?= $form->fieldB($model, 'time_end')->textDate(['format' => "DD-MM-YYYY HH:mm"])->label() ?>

            <?php ActiveFormC::end1(['update' => $model->id]); ?>
        </div>
    </div>
</div>