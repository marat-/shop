<?php

class AuthController extends Controller {
    public $layout = '//layouts/main_bootstrap';

	public function actionIndex() {
        Yii::app()->request->cookies['cookie_check'] = new CHttpCookie('cookie_check', 1);
        $oUsers = new Users('login');
        $this->render('auth', array("user" => $oUsers));
	}

    /**
     * Проверка введенных пользователем логина/пароля или проверочного кода
     * Функция принимает исходные данные в POST (из формы на сайте) и в зависимости от типа пришедших данных
     * производит проверку на корректность введенной пары логин-пароль или проверочного кода, отправленного на мобильный телефон.
     * Работа происходит через Ajax, результат возвращается в браузер в формате JSON. Выводится либо сообщение об ошибке,
     * либо логическое (bool) TRUE, когда ошибок не возникает.
     */
    public function actionCheck() {
        $arUserData = Yii::app()->request->getPost('Users');
        $sEmail = trim($arUserData['email']);
        $sPassword = trim($arUserData['password']);
        $sIp = $this->get_user_ip();

        $arLoginData = array(
            "login" => $sEmail,
            "referrer" => Yii::app()->getBaseUrl(true),
            "ip" => $sIp
        );

        // Проверяем корректность пары логин-пароль или смену пароля
        $oIdentity=new UserIdentity($sEmail, $sPassword);
        if($oIdentity->authenticate()) {
            Yii::app()->user->login($oIdentity);
            echo "true";
        }
        else {
            echo json_encode(array("msg" => 'error|' . $oIdentity->errorMessage, "html_msg" => isset($sCaptchaView) ? $sCaptchaView : ""));
        }


        /*$sCaptcha = trim(Yii::app()->request->getPost('captcha'));
        if(($m = $this->logon($sEmail, $sPassword, $sCaptcha))) {
            $this->show_data(json_encode($m), "json");
            $arLoginData['error'] = $m['emb_msg'];
            $arLoginData['success'] = 0;
        } else {
            $this->show_data(json_encode(true), "json");
            $arLoginData['success'] = 1;
        }
        $this->user_model->dbInsert("gamma.dbo.sp_login_attempts", $arLoginData);*/


    }

    public function actionGenPass()
    {
        $user = new Users();
        echo (CRYPT_BLOWFISH === 1) ? 'CRYPT_BLOWFISH is enabled!' : 'CRYPT_BLOWFISH is not available';
        var_dump(CRYPT_BLOWFISH);
        //var_dump(crypt('byrke5l8byu', $this->generateSalt()));
    }

    function generateSalt($cost = 13)
    {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
            throw new Exception("cost parameter must be between 4 and 31");
        }
        $rand = array();
        for ($i = 0; $i < 8; $i += 1) {
            $rand[] = pack('S', mt_rand(0, 0xffff));
        }
        $rand[] = substr(microtime(), 2, 6);
        $rand = sha1(implode('', $rand), true);
        $salt = '$2a$' . str_pad((int) $cost, 2, '0', STR_PAD_RIGHT) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
        return $salt;
    }
}