<?php echo CHtml::label('Проверочный код', 'verifyCode'); ?>
<div>
    <?php $this->widget('CCaptcha'); ?>
    <?php echo CHtml::textField('verifyCode', '', array('class' => 'input-block-level', 'id' => 'verifyCode'));?>
</div>
