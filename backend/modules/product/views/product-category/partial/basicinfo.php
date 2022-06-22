
<?=

$form->field($model, 'name', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => $model->getAttributeLabel('name')
])->label($model->getAttributeLabel('name'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>


<?=

$form->field($model, 'status', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12" style="padding-top: 8px;">{input}{error}{hint}</div>'
])->checkbox([
    'class' => 'js-switch',
    'label' => NULL,
])->label($model->getAttributeLabel('status'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>

<?=

$form->field($model, 'order', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => $model->getAttributeLabel('order')
])->label($model->getAttributeLabel('order'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>

<?=

$form->field($model, 'ckedit_desc', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12" style="padding-top: 8px;">{input}{error}{hint}</div>'
])->checkbox([
    'label' => NULL
])->label($model->getAttributeLabel('ckedit_desc'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>

<?=
$form->field($model, 'description', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textArea([
    'class' => 'form-control',
    'rows' => 4
])->label($model->getAttributeLabel('description'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>