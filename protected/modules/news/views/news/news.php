<?php Yii::app()->clientScript->registerPackage('news_resource'); ?>
<div class="span8">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button',
        'type'=>'success',
        'label'=>'Создать',
        'loadingText'=>'Загрузка...',
        'htmlOptions'=>array('id'=>'b-create-news'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'filter' => $gridDataProvider,
        'dataProvider'=>$gridDataProvider->search(),
        'template'=>"{summary} {pager} {items} {pager}",
        'id'=>'news-grid',
        'rowHtmlOptionsExpression' => 'array("id"=>$data->primaryKey)',
        'rowCssClassExpression'=>'$data->del?"row-closed":"row-open"',
        'columns'=>array(
            array('name'=>'id','htmlOptions'=>array('style'=>'width: 5%'),),
            array('name'=>'date','value' => 'date("d.m.Y", strtotime($data->date));','htmlOptions'=>array('style'=>'width: 12%'),),
            array('name'=>'header', 'value' => '$data->newsDetails[0]->header', 'htmlOptions'=>array('style'=>'width: 73%'),),
            array(
                'value'=>array($this,'renderButtons'), 'htmlOptions'=>array('style'=>'width: 10%')
            ),
            /*array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'htmlOptions'=>array('style'=>'width: 50px'),
                //'template'=>'{view}{update}{delete}',
                'htmlOptions'=>array('style'=>'width: 50px; vertical-align: middle;'), 'headerHtmlOptions'=>array('width'=>'50px'),
                /*'buttons'=>array (
                    'view' => array (
                        'label'=>'Просмотр',
                        'options' => array('class'=>'b-news-view', 'rel'=>'tooltip', 'href'=>'#', 'style'=>'display: inline-block; width: 20px;'),
                    ),
                    'update' => array (
                        'label'=>'Редактировать',
                        'options' => array('class'=>'b-news-edit', 'rel'=>'tooltip', 'href'=>'#', 'style'=>'display: inline-block; width: 20px;'),
                    ),
                    'delete' => array (
                        'label'=>'Удалить',
                        'options' => array('class'=>'b-news-delete', 'rel'=>'tooltip', 'href'=>'#', 'style'=>'display: inline-block; width: 20px;'),
                    ),
                ),*/
                /*'template'=>'<a class="b-news-view" rel="tooltip" href="#" data-original-title="$data->newsDetails[0]->header"><i class="icon-eye-open"></i></a>
                         <a class="b-news-update" rel="tooltip" href="#" data-original-title="Редактировать"><i class="icon-pencil"></i></a>
                         <a class="b-news-delete" rel="tooltip" href="#" data-original-title="Удалить"><i class="icon-trash"></i></a>
                         ',
            ),*/
        ),
    )); ?>
    <div class='container news-container'></div>
</div>
