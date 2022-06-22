
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

<?php $model_cats = new common\models\product\ProductCategory(); ?>

<?=

$form->field($model, 'category_id', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList($model_cats->optionsCategory(), [
    'class' => 'form-control',
])->label($model->getAttributeLabel('category_id'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>


<?=

$form->field($model, 'price', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => 'Nhập giá'
])->label($model->getAttributeLabel('price'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>

<?=

$form->field($model, 'price_market', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->textInput([
    'class' => 'form-control',
    'placeholder' => 'Nhập giá chưa giảm'
])->label($model->getAttributeLabel('price_market'), [
    'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
])
?>

<?=

$form->field($model, 'loaimau_id', [
    'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
])->dropDownList(\common\models\LoaiMau::getLoaimau(),['prompt' => 'Chọn loại mẫu'])->label($model->getAttributeLabel('loaimau_id'), [
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