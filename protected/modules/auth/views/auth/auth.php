<link rel="stylesheet" type="text/css" href="<?php echo $this->assetsUrl; ?>/auth.css"/>
<?php
/* @var $this DefaultController */

$this->breadcrumbs = array(
    $this->module->id,
);
?>
<div class="row-fluid">
    <div class="offset1 span11">
        <h1>Добро пожаловать в наш магазин!</h1>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
    </div>
</div>
<div class="row-fluid">
    <div class="offset1 span5">
        <?php
        $this->beginWidget('bootstrap.widgets.TbHeroUnit');
        ?>
        <h2>Вход в систему</h2>
        <h4>
            Войдите в систему, чтобы покупать товары и управлять учетной записью.
        </h4>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'verticalForm',
            'htmlOptions'=>array('class'=>'form-signin'),
            'action' => Yii::app()->baseUrl . "/auth/login"
        )); ?>
        <?php echo $form->textFieldRow($model, 'email', array('class'=>'input-block-level')); ?>
        <?php echo $form->passwordFieldRow($model, 'password', array('class'=>'input-block-level')); ?>
        <label class="checkbox">
            <input type="checkbox" value="remember-me"> Оставаться в системе
        </label>
        <?$this->widget('bootstrap.widgets.TbButton', array(
            'type' => 'primary',
            'size' => 'large',
            'label' => 'Вход в систему',
        ));
        ?>
        <?php $this->endWidget(); ?>

        <?php $this->endWidget(); ?>
    </div>
    <div class="span5">
        <?php
        $this->beginWidget('bootstrap.widgets.TbHeroUnit');
        ?>
        <center>
            <h2>Первый раз у нас?</h2>
            <h4>Начните прямо сейчас. Сделать это быстро и легко!</h4>
            <?$this->widget('bootstrap.widgets.TbButton', array(
                'type' => 'primary',
                'size' => 'large',
                'label' => 'Регистрация',
            ));
            ?>
        </center>
        <?php $this->endWidget(); ?>
    </div>
</div>