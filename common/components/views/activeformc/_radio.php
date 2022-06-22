<div id="box-radio-<?= $model->attribute  ?>" class="form-group box-radio field-radio-<?= $model->attribute.(isset($options['required']) && $options['required'] ? ' required' : '')  ?>" >
    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="radio-<?= $model->attribute  ?>"><?= $model->getAttrName()  ?></label><div class="col-md-10 col-sm-10 col-xs-12">
<?php 
foreach ($options['arr_select'] as $key => $value) {
    echo '<label><input type="radio" '.($model->model[$model->attribute] === $key ? 'checked' : '').' id="radio-'.$model->attribute.'-'.$key.'" name="'.$model->getClassName().'['.$model->attribute.']" value="'.$key.'"> '.$value.'</label>';
}
?>
<div class="help-block"></div></div></div>
<?php if((isset($options['required']) && $options['required'])) { ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var radio<?= $model->attribute  ?> = $('#box-radio-<?= $model->attribute  ?>').find('input');
			radio<?= $model->attribute  ?>.change(function () {
				$('#box-radio-<?= $model->attribute  ?>').find('.help-block').html('');
				$('form').attr('<?= $model->attribute ?>', '1');
			})
			$('form').submit(function () {
				if($(this).attr('<?= $model->attribute ?>') != '1') {
					$('#box-radio-<?= $model->attribute  ?>').find('.help-block').html('<?= $model->getAttrName().' '.Yii::t('app', 'not_null') ?>');
					return false;
				}
			});
			<?= ($model->model[$model->attribute]) ? "$('form').attr('".$model->attribute."', '1');" : '' ?>
		});
	</script>
<?php } ?>
