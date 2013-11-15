<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main_bootstrap';
    public $arMenu;
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    /*
     * Определение пользовательского IP
     */
    function get_user_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Вывод в браузер raw-данных
     * При работе через Ajax предваряет выводимые данные корректным заголовоком. Пытается также обойти проблемы плагина jQuery.form,
     * если необходимо вывести данные а формате JSON или Sctipt, для чего результат оборачивает в тэг <textarea>.
     *
     * @param mixed $data Данные для вывода в браузер
     * @param string $content_type Тип данных
     */
    public function showPage($oController,$sView, $arParams, $bPartial=false) {
        if($bPartial) {
            $this->renderPartial($sView, $arParams);
        } else {
            // Обрабатываем и формируем меню
            $criteria=new CDbCriteria;
            $criteria->addCondition("active = 1");
            $arMenuItems=Menu::model()->findAll($criteria);
            $this->arMenu = array(array('label'=>'Разделы'));
            foreach($arMenuItems as $key=>$value) {
                $this->arMenu[] = array('label'=>$value['name'], 'icon'=>$value['icon'], 'url'=> Yii::app()->createUrl($value['path']), 'active'=>$oController->getId() == $value['controller'], 'visible' => Yii::app()->user->checkAccess('adminPanel'));
            }
            $this->render($sView, $arParams);
        }
    }
}