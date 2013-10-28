<fieldset>
    <p>
        <?php echo CHtml::label('Заголовок новости','NewsDetails[header][' . $language_id . ']');?>
        <?php echo CHtml::textField('NewsDetails[header][' . $language_id . ']', $news_details_model->header, array('class' => 'input-block-level', 'id' => 'NewsDetails[header][' . $language_id . ']'));//$form->textFieldRow($news_details_model, 'header', array('class' => 'input-block-level')); ?>
        <?php// echo $form->error($news_details_model,'header'); ?>
        <?php echo CHtml::label('Краткий текст новости','NewsDetails[brief][' . $language_id . ']');?>
        <?php $this->widget('application.extensions.cleditor.ECLEditor', array(
            //'model' => $news_details_model,
            'name' => 'NewsDetails[brief][' . $language_id . ']',
            'options' => array(
                'width' => '500',
                'height' => 250,
                'useCSS' => true,
            ),
            'value' => $news_details_model->brief,
        )); ?>
        <?php// echo $form->error($news_details_model,'brief'); ?>
        <?php echo CHtml::label('Текст новости','NewsDetails[text][' . $language_id . ']');?>
        <?php $this->widget('application.extensions.cleditor.ECLEditor', array(
            //'model' => $news_details_model,
            'name' => 'NewsDetails[text][' . $language_id . ']',
            'options' => array(
                'width' => '500',
                'height' => 250,
                'useCSS' => true,
            ),
            'value' => $news_details_model->text,
        )); ?>
        <?php// echo $form->error($news_details_model,'text'); ?>
    </p>
</fieldset>
