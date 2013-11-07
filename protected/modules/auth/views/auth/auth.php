<?php Yii::app()->clientScript->registerPackage('auth_resource'); ?>
<div class="container">
    <div class="row">
        <div class="offset1 span3">
            <a href="<?php echo Yii::app()->request->getBaseUrl(true);?>" id="nefco-logo-link">
                <img width="270" height="70" border="0" alt="Nefis Group" src="<?php echo Yii::app()->request->baseUrl . "/images/logo/site_logo.gif";?>" id="nefco-logo">
            </a>
        </div>
        <div class="form-logon-heading-container span8">
            <h2 class="form-logon-heading">Добро пожаловать!</h2>
        </div>
    </div>
    <div class="row auth-container">
        <div class="offset1 span5 sign-in-container">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'auth-form',
                'htmlOptions' => array('class' => 'form-signin', 'enctype' => 'multipart/form-data', 'rel' => Yii::app()->request->baseUrl . '/admin'),
                'action'=>Yii::app()->request->baseUrl . '/auth/auth/check',
                //'enableAjaxValidation'=>true,
                'enableClientValidation'=>false,
                'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true),
            )); ?>
                <h3 class="form-signin-heading">Авторизация</h3>
                <div class="message-container">
                    <noscript>
                        <div class="error" id="js_error">
                            <span class="text-error">Ваш браузер не поддерживает JavaScript. Работа на портале будет невозможна!</span>
                        </div>
                    </noscript>

                    <div class="error hidden" id="cookie_error">
                        <span class="text-error">Ваш браузер не поддерживает сохранение COOKIE. Работа на портале будет невозможна!</span>
                    </div>

                    <div class="error<?= isset($session_error) && $session_error ? "" : " hidden"; ?>" id="session_error">
                        <span class="text-error">Ваш браузер не поддерживает сохранение сессии. Работа на портале будет невозможна!</span>
                    </div>
                </div>
                <?php echo $form->textField($user,'email', array('class'=>'login input-block-level', 'placeholder'=>'Email')); ?>
                <?php echo $form->error($user,'email'); ?>
                <?php echo $form->passwordField($user,'password', array('class'=>'login input-block-level', 'placeholder'=>'Пароль')); ?>
                <?php echo $form->error($user,'password'); ?>
                <div class="captcha-container hidden"></div>

                <?php echo CHtml::ajaxSubmitButton('Войти',
                    array('/auth/auth/check'),
                    array(
                        'dataType'=>'json',
                        'success'=>'function(data) {formSubmitHandler(data, $("#auth-form"))}',
                    ),
                    array("class"=>"btn btn-large btn-primary", "id" => "enter")
                );
                ?>
                <p class="enter-problems"><a href="" class="enter-problems-link">Забыли пароль?</a></p>
                <div class="clearfix"></div>
            <?php $this->endWidget(); ?>
        </div>
        <div class="span6">
            <div class="form-ad">
                <div class="error hidden" id="browser_error">
                                <span class="text-error">
                                    Ваш браузер или его версия полностью не поддерживаются. Возможны перебои в работе портала.<br><br>
                                    Мы рекомендуем установить и использовать последнюю доступную на текущий момент версию:<br>
                                    <a href="http://www.mozilla.org/ru/firefox/fx/" target="_blank">Mozilla Firefox</a> |
                                    <a href="http://www.google.ru/chrome" target="_blank">Google Chrome</a> |
                                    <a href="http://www.apple.com/ru/safari/download/" target="_blank">Apple Safari</a>.
                                </span>
                </div>
                <?if(isset($news) && is_array($news) && count($news) > 0):?>
                    <?foreach($news as $item):?>
                        <div>
                            <h4><?=$item['date_begin'];?></h4>
                            <p><?=$item['header'];?></p>
                            <p><a href="#" class="btn news-detail-link" news_id="<?=$item['id'];?>" >Подробнее »</a></p>
                            <hr />
                        </div>
                    <?endforeach;?>
                <?else:?>
                    <h4><?=date("d.m.Y H:i");?></h4>
                    <p>На данный момент новости отсутствуют.</p>
                <?endif;?>
            </div>
        </div>
    </div>
</div>