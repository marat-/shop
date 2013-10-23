<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'news-form',
    'htmlOptions' => array('class' => 'news-form', 'enctype' => 'multipart/form-data'),
    'action'=>Yii::app()->request->baseUrl . '/news/news/edit',
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>false,
    'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true),
)); ?>
    <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'news-create-dialog')); ?>

        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h4>Создание новости.</h4>
        </div>

        <div class="modal-body">
            <?php echo $form->errorSummary($news_model); ?>
            <?php echo $form->errorSummary($news_details_model); ?>
            <p>
                <?php echo $form->textFieldRow($news_details_model, 'header', array('class' => 'input-block-level')); ?>
                <?php echo $form->error($news_details_model,'header'); ?>
                <?php echo $form->textFieldRow($news_details_model, 'brief', array('class' => 'input-block-level')); ?>
                <?php echo $form->error($news_details_model,'brief'); ?>
                <?php echo CHtml::activeLabel($news_details_model,'text');?>
                <?php $this->widget('application.extensions.cleditor.ECLEditor', array(
                    'model' => $news_details_model,
                    'attribute' => 'text',
                    'options' => array(
                        'width' => '500',
                        'height' => 250,
                        'useCSS' => true,
                    ),
                    'value' => $news_details_model->text,
                )); ?>
                <?php echo $form->error($news_details_model,'text'); ?>
                <?php echo CHtml::activeLabel($news_model,'date');?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $news_model,
                    'language'=>'ru',
                    'name' => 'date',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat'=>'dd.mm.yy',
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;'
                    ),
                    'attribute' => 'date',
                    'value' => $news_model->date,
                ));?>
                <?php echo $form->error($news_model,'date'); ?>

                <?php echo CHtml::activeHiddenField($news_model, 'image'); ?>

                <?php $this->widget('ext.EAjaxUpload.EAjaxUpload',
                    array(
                        'id'=>'uploadFile_image',
                        'config'=>array(
                            'action'=>Yii::app()->createUrl('news/news/uploadImages'),
                            'allowedExtensions'=>array("jpg", "gif", "png"),
                            'sizeLimit'=>10*1024*1024,
                            'minSizeLimit'=>10,
                            'onComplete'=>"js:function(id, fileName, responseJSON){
                                $('#News_image').val(responseJSON.filename);
                                $('#image').empty();
                                $('#image').append('<img src=\"../../upload/small-' + responseJSON.filename + '\">');
                            }",
                        )
                    )); ?>
                <div id="image" class="pull-left"></div>
                <?php echo $form->error($news_model,'image'); ?>


                <?php echo CHtml::activeHiddenField($news_model, 'id'); ?>
                <?php echo CHtml::activeHiddenField($news_model, 'author', array("value"=>"1")); ?>
                <?php echo CHtml::activeHiddenField($news_details_model, 'language_id', array("value"=>"1")); ?>
            </p>
        </div>

        <div class="modal-footer">
            <?php echo CHtml::ajaxSubmitButton('Сохранить',
                array('/news/news/edit'),
                array(
                    'success'=>'function(html){if (html == ""){ $("#news-grid").yiiGridView("update", {}); $("#news-create-dialog").modal("hide");}}',
                    'beforeSend' => 'function(){
                        $.fn.yiiactiveform.validate("#news-form", function(a){console.log(a);}, function(a){console.log(a)});
                     }',
                ),
                array("class"=>"btn btn-primary", "id" => "saveNews")
            );
            ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Закрыть',
                'url'=>'#',
                'htmlOptions'=>array('data-dismiss'=>'modal', "id" => "close-news-dialog"),
            )); ?>
        </div>

    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>