<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
        <?php Yii::app()->bootstrap->register(); ?>
        <!--[if lt IE 9]>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/html5shiv/dist/html5shiv.js"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>        
        <?php
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => 'inverse', // null or 'inverse'
            'brand' => 'Project name',
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
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'htmlOptions' => array('class' => 'pull-right'),
                    'items' => array(
                        array('label' => 'Вход', 'url' => 'index.php?r=auth'),
                        array('label' => 'Регистрация', 'url' => '#'),
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
                <?php echo $content; ?>
            </div>

            <hr>

            <footer>
                <p>&copy; Company 2012</p>
            </footer>

        </div>
    </body>
</html>
