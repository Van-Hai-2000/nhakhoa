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

            <?= $form->fieldB($model, 'hour_confinement')->textInput()->label() ?>

            <?= $form->fieldB($model, 'sale')->textInput()->label() ?>

            <?=
                $form->fieldB($model, 'transfer_fee_type')->dropDownList([
                    1 => 'Phí tính theo phần trăm',
                    0 => 'Phí tính theo V',
                ])->label()
            ?>

            <?= $form->fieldB($model, 'transfer_fee')->textInput(['type' => 'number'])->label() ?>

            <?php ActiveFormC::end1(['update' => $model->id]); ?>
        </div>
    </div>
</div>