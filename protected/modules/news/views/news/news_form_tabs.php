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
        <h4><?php echo !$news_model->id ? "Создание новости." : "Редактирования новости.";?></h4>
    </div>

    <div class="modal-body">
        <fieldset>
            <legend>Общая информация</legend>
            <?php $this->widget('bootstrap.widgets.TbTabs', array(
                'type'=>'tabs', // 'tabs' or 'pills'
                'tabs'=>$news_details_form,
                'htmlOptions' => array(),
            )); ?>
            <?php echo $form->errorSummary($news_model); ?>
            <p>
                <?php echo CHtml::activeLabel($news_model,'date');?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $news_model,
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

                <?php echo CHtml::activeHiddenField($news_model, 'id'); ?>
                <?php echo CHtml::activeHiddenField($news_model, 'author', array("value"=>"1")); ?>
            </p>
        </fieldset>
        <fieldset>
            <legend>Главное изображение</legend>
            <?php $this->widget('ext.EAjaxUpload.EAjaxUpload',
                array(
                    'id'=>'uploadFile_image',
                    'config'=>array(
                        'action'=>Yii::app()->createUrl('news/news/uploadImages'),
                        'allowedExtensions'=>array("jpg", "gif", "png"),
                        'sizeLimit'=>10*1024*1024,
                        'minSizeLimit'=>10,
                        'onComplete'=>"js:function(id, fileName, responseJSON){
                                    $('#image').empty();
                                    $('#image').append('<input id=\"News_image\" name=\"News[image]\" type=\"hidden\" value=\"'+responseJSON.filename+'\">' +
                                                        '<img src=\"../../upload/small-' + responseJSON.filename + '\">' +
                                                        '<a href=\"#\" class=\"remove-image\"><i class=\"icon-remove\"></i></a>');
                                }",
                    )
                )); ?>
            <div id="image" class="pull-left">
                <?if($news_model->image):?>
                    <img src="../../upload/small-<?php echo $news_model->image; ?> ">
                    <a href="#" class="remove-image"><i class="icon-remove"></i></a>
                <?endif;?>
                <?php echo CHtml::activeHiddenField($news_model, 'image'); ?>
            </div>
            <?php echo $form->error($news_model,'image'); ?>
        </fieldset>
        <fieldset>
            <legend>Дополнительные изображения</legend>
            <div class="control-group">
                <?php $this->widget('ext.EAjaxUpload.EAjaxUpload',
                    array(
                        'id'=>'uploadFile_gallery',
                        'config'=>array(
                            'action'=>Yii::app()->createUrl('news/news/uploadImages'),
                            'allowedExtensions'=>array("jpg", "gif", "png"),
                            'sizeLimit'=>10*1024*1024,
                            'multiple'=> true,
                            'minSizeLimit'=>10,
                            'onComplete'=>"js:function(id, fileName, responseJSON){
                                    $('#images_preview').append('<span class=\"news_gallery\">' +
                                                                    '<input name=\"NewsGallery[image][]\" type=\"hidden\" value=\"'+responseJSON.filename+'\">' +
                                                                    '<img src=\"../../upload/small-' + responseJSON.filename + '\" width=\"100px\">' +
                                                                    '<a href=\"#\" class=\"remove-image-from-gallery\"><i class=\"icon-remove\"></i></a>' +
                                                                '</span>');
                                }",
                        )
                    ));
                ?>
                <div class="controls">
                    <div id="images_preview">
                        <?php if ($news_gallery_model->images) foreach ($news_gallery_model->images as $image): ?>
                            <span class="news_gallery">
                                    <input name="NewsGallery[image][]" type="hidden" value="<?php echo $image->image; ?>">
                                    <img src="../../upload/small-<?php echo $image->image; ?>" width="100px">
                                    <a href="#" class="remove-image-from-gallery" img_id="<?php echo $image->id; ?>"><i class="icon-remove"></i></a>
                                </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="modal-footer">
        <?php echo CHtml::ajaxSubmitButton('Сохранить',
            array('/news/news/edit'),
            array(
                'success'=>'function(html){
                    if (html == ""){
                        $("#news-grid").yiiGridView("update", {});
                        $("#news-create-dialog").modal("hide");

                        removeCLEditorInstances();
                    }
                }',
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
            )
        ); ?>
    </div>

<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>