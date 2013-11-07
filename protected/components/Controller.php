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
	public $layout='//layouts/column1';
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
    public function show_data(& $data, $content_type = "html", $callback = "") {
        // Хак для js-плагина jQuery.form. Нужен для передачи браузеру данных в формате json или script, если выгружается файл
        if(isset($_REQUEST['sessid'])&&$_REQUEST['sessid']||isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
            $xhr = true;
        }else{
            $xhr = false;
        }
        if (!$xhr && ($content_type == "json" || $content_type == "script")) {
            $data = '<textarea>' . $data . '</textarea>';
        } else {
            $this->output->set_content_type($content_type);
        }

        // Добавляем проверочную информацию, если необходимо.
        if ($callback) {
            $data = $callback . "(" . $data . ")";
        }

        echo $data;
    }
}