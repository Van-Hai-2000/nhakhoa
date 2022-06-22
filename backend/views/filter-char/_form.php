<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FilterChar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="filter-char-form">
	 <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <?php
	            $form = ActiveForm::begin([
	                'id' => 'news-form',
	                'enableClientValidation' => false,
	                'enableAjaxValidation' => false,
	                'validateOnSubmit' => true,
	                'validateOnChange' => true,
	                'validateOnType' => true,
	                'options' => [
	                    'class' => 'form-horizontal'
	                ]
	            ]);
	            ?>

	            <div class="x_panel">
	                <div class="x_title">
	                    <h2><i class="fa fa-bars"></i> <?= Html::encode($this->title) ?> </h2>
	                    <div class="clearfix"></div>
	                </div>
	                <div class="x_content">

					    <?=
		                    $form->field($model, 'characters', [
		                        'template' => '{label}<div class="col-md-10 col-sm-10 col-xs-12">{input}{error}{hint}</div>'
		                    ])->textInput([
		                        'placeholder' => 'Nhập danh sách từ cách nhau bởi ","'
		                    ])->label($model->getAttributeLabel('characters'), [
		                        'class' => 'control-label col-md-2 col-sm-2 col-xs-12'
		                    ]);
		                ?>

					    <div class="form-group">
					        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
					    </div>
					 </div>
				</div>
    		<?php ActiveForm::end(); ?>

		</div>
	</div>
</div>
