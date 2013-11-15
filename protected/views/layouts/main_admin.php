<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php Yii::app()->clientScript->registerPackage('jquery_js'); ?>
        <?php Yii::app()->bootstrap->register(); ?>
        <!--[if lt IE 9]>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/html5shiv/dist/html5shiv.js"></script>
        <![endif]-->
        <?php
            Yii::app()->clientScript->registerScript('helpers', '
              yii = {
                  urls: {
                      saveEdits: '.CJSON::encode(Yii::app()->createUrl('edit/save')).',
                      base: '.CJSON::encode(Yii::app()->baseUrl).'
                  }
              };
            ',CClientScript::POS_HEAD);
        ?>
        <?php Yii::app()->clientScript->registerPackage('common_resource'); ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>        
        <?php
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => 'inverse', // null or 'inverse'
            'brand' => 'ОАО "Нэфис Косметикс"',
            'brandUrl' => '#',
            'collapse' => true, // requires bootstrap-responsive.css
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'items' => array(
                        array('label' => 'Home', 'url' => '#', 'active' => true),
                        array('label' => 'Link', 'url' => '#'),
                        array('label' => 'Dropdown', 'url' => '#', 'items' => array(
                                array('label' => 'Action', 'url' => '#'),
                                array('label' => 'Another action', 'url' => '#'),
                                array('label' => 'Something else here', 'url' => '#'),
                                '---',
                                array('label' => 'NAV HEADER'),
                                array('label' => 'Separated link', 'url' => '#'),
                                array('label' => 'One more separated link', 'url' => '#'),
                            )),
                    ),
                ),
                '<form class="navbar-search pull-left" action=""><input type="text" class="search-query span3" placeholder="Поиск"></form>',
                $this->widget('bootstrap.widgets.TbProgress', array(
                    'type'=>'info', // 'info', 'success' or 'danger'
                    'percent'=>100, // the progress
                    'striped'=>true,
                    'animated'=>true,
                    'htmlOptions'=> array('class' => 'pull-right span1 nav', 'id'=>'loadProgress'),
                ), true),
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'htmlOptions' => array('class' => 'pull-right'),
                    'items' => array(
                        array('label' => 'Выход', 'url' => Yii::app()->request->baseUrl . '/auth/auth/logout'),
                        array('label' => 'Dropdown', 'url' => '#', 'items' => array(
                            array('label' => 'Action', 'url' => '#'),
                            array('label' => 'Another action', 'url' => '#'),
                            array('label' => 'Something else here', 'url' => '#'),
                            '---',
                            array('label' => 'Separated link', 'url' => '#'),
                        )),
                    ),
                ),
            ),
        ));
        ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="row-fluid">
                    <div class="offset1 span11">
                        <h4>Раздел администратора.</h4>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <?php $this->widget('bootstrap.widgets.TbMenu', array(
                            'type'=>'list',
                            'items'=>$this->arMenu/*array(
                                array('label'=>'Разделы'),
                                array('label'=>'Новости', 'icon'=>'book', 'url'=> Yii::app()->createUrl("/admin/admin/showNews"), 'active'=>true),
                                array('label'=>'Продукция', 'icon'=>'icon-th', 'url'=>'#'),
                                array('label'=>'Пользователи', 'icon'=>'icon-user', 'url'=>'#'),
                            ),*/
                        )); ?>
                    </div>
                    <?php echo $content; ?>
                </div>
            </div>

            <hr>

            <footer>
                <p>&copy; Company 2012</p>
            </footer>

        </div>
    </body>
</html>
