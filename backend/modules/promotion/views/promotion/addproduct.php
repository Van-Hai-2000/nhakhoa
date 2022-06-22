<div class="widget widget-box no-border">
    <div class="widget-header"><h4>
            <?php echo Yii::t('product', 'promotion_addproduct') . " '" . $model->name . "'"; ?>
        </h4></div>
    <div class="widget-body" style="border: none;">
        <div class="widget-main">
            <div class="row" style="overflow: hidden;">
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="widget-box" id="searchproduct">
                                    <div class="widget-header header-color-grey">
                                        <h4 class="lighter smaller"><?php echo Yii::t('product', 'product_search'); ?></h4>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main padding-8">
                                            <div class="search-active-form" style="position: relative; margin-top: 10px;">
                                                <?php
                                                $this->renderPartial('_searchproduct', array(
                                                    'productModel' => $productModel,
                                                ));
                                                ?>
                                            </div><!-- search-form -->
                                            <?php
                                            /* @var $this NewsController */
                                            /* @var $model News */

                                            Yii::app()->clientScript->registerScript('search', "
                                            $('.search-active-form form').submit(function(){
                                                    $('#products-grid').yiiGridView('update', {
                                                            data: $(this).serialize()
                                                    });
                                                    return false;
                                            });
                                            ");
                                            ?>
                                            <?php
                                            Yii::import('common.extensions.LinkPager.LinkPager');
                                            $this->widget('zii.widgets.grid.CGridView', array(
                                                'id' => 'products-grid',
                                                'summaryText' => '',
                                                'dataProvider' => $productModel->search(),
                                                'itemsCssClass' => 'table table-bordered table-hover vertical-center',
                                                'filter' => null,
                                                'enableSorting' => false,
                                                'pager' => array(
                                                    'class' => 'LinkPager',
                                                    'header' => '',
                                                    'nextPageLabel' => '&raquo;',
                                                    'prevPageLabel' => '&laquo;',
                                                    'lastPageLabel' => Yii::t('common', 'last_page'),
                                                    'firstPageLabel' => Yii::t('common', 'first_page'),
                                                ),
                                                'columns' => array(
                                                    'name' => array(
                                                        'name' => 'name',
                                                        'htmlOptions' => array(
                                                            'style' => 'padding-top:0px; padding-bottom:0px; font-size: 12px;',
                                                        ),
                                                    ),
                                                    'action' => array(
                                                        'header' => '',
                                                        'type' => 'raw',
                                                        'htmlOptions' => array('style' => 'width: 60px; text-align:center; font-size: 20px; padding: 0px;'),
                                                        'value' => function($data) {
                                                    return '<a href="#" onclick="return AddToChoice(\'' . $data['id'] . '\',this); return false;" title="' . Yii::t('product', 'product_add') . '"><i class="icon-chevron-right"></i></a>'
                                                            . '<a class="hidden" style="color: #AC0912;" href="#" onclick="return RemoveChoice(\'' . $data['id'] . '\',this); return false;" title="' . Yii::t('product', 'product_add') . '"><i class="icon-remove"></i></a>';
                                                }
                                                    ),
                                                ),
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5" id="choicedproduct">
                                <?php
                                $form = $this->beginWidget('CActiveForm', array(
                                    'id' => 'product-groups-form',
                                    'htmlOptions' => array('class' => 'form-horizontal'),
                                    'enableAjaxValidation' => false,
                                ));
                                ?>
                                <div class="widget-box">
                                    <div class="widget-header header-color-green2">
                                        <h4 class="lighter smaller"><?php echo Yii::t('product', 'choiced_product') ?></h4>
                                        <div class="widget-toolbar no-border">
                                            <?php echo CHtml::submitButton(Yii::t('product', 'choiced_product_save'), array('class' => 'btn btn-xs btn-primary', 'id' => 'btnProductSave')); ?>
                                        </div>

                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main padding-8">
                                            <table class="table table-bordered table-hover vertical-center products">
                                                <thead>
                                                    <tr>
                                                        <th id="products-grid_c0">
                                                            <?php echo $productModel->getAttributeLabel('name'); ?>
                                                        </th>
                                                        <th id="products-grid_caction">&nbsp;</th></tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                echo CHtml::hiddenField('products', '', array('id' => 'choicedProducts'));
                                ?>
                                <?php
                                $this->endWidget();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->renderPartial('partial/script', array('isAjax' => $isAjax)); ?>