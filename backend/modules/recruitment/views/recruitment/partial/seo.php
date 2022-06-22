<?=
$form->field($model, 'meta_title', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput(['maxlength' => true])->label($model->getAttributeLabel('meta_title'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>
<?=
$form->field($model, 'meta_description', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput(['maxlength' => true])->label($model->getAttributeLabel('meta_description'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>
<?=
$form->field($model, 'meta_keywords', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput(['maxlength' => true])->label($model->getAttributeLabel('meta_keywords'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
]);
?>