<?php

use yii\helpers\Html;
?>

<?=
$form->field($model_info, 'benefit', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textArea(['rows' => 4])->label($model_info->getAttributeLabel('benefit'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model_info, 'description', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textArea(['rows' => 4])->label($model_info->getAttributeLabel('description'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model_info, 'job_requirement', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textArea(['rows' => 4])->label($model_info->getAttributeLabel('job_requirement'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>

<?=
$form->field($model_info, 'record_consists', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textArea(['rows' => 4])->label($model_info->getAttributeLabel('record_consists'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>