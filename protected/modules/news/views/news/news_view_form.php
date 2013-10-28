
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'news-view-dialog')); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Просмотр новости.</h4>
    </div>

    <div class="modal-body">
        <fieldset>
            <legend>Общая информация</legend>
            <p>
                <?php echo $news_details_model->header;?>
                <?php echo $news_details_model->brief;?>
                <?php echo $news_details_model->text;?>

                <?php echo $news_model->date;?>

            </p>
        </fieldset>
        <fieldset>
            <legend>Главное изображение</legend>
            <div id="image" class="pull-left">
                <img src="../../upload/small-<?php echo $news_model->image; ?> ">
            </div>
        </fieldset>
        <fieldset>
            <legend>Дополнительные изображения</legend>
            <div class="control-group">
                <div class="controls">
                    <div id="images_preview">
                        <?php if ($news_gallery_model->images) foreach ($news_gallery_model->images as $image): ?>
                            <span class="news_gallery">
                                    <img src="../../upload/small-<?php echo $image->image; ?>" width="100px">
                                </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Закрыть',
            'url'=>'#',
            'htmlOptions'=>array('data-dismiss'=>'modal', "id" => "close-view-dialog"),
        )); ?>
    </div>

<?php $this->endWidget(); ?>
