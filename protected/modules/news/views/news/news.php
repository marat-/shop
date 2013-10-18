<div class="span8">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button',
        'type'=>'success',
        'label'=>'Создать',
        'loadingText'=>'Загрузка...',
        'htmlOptions'=>array('id'=>'buttonStateful'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'filter' => $gridDataProvider,
        'dataProvider'=>$gridDataProvider->search(),
        'template'=>"{items}",
        'columns'=>array(
            array('name'=>'id','htmlOptions'=>array('style'=>'width: 10px'),),
            array('name'=>'date','value' => 'date("d.m.Y", strtotime($data->date));','htmlOptions'=>array('style'=>'width: 50px'),),
            array('name'=>'header', 'value' => '$data->newsDetails[0]->header','htmlOptions'=>array('style'=>'width: 50px'),),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'htmlOptions'=>array('style'=>'width: 50px'),
            ),
        ),
    )); ?>
</div>