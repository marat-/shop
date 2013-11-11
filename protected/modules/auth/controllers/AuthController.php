<?php

class AuthController extends Controller {
    public $layout = '//layouts/main_bootstrap';

	public function actionIndex() {
        Yii::app()->request->cookies['cookie_check'] = new CHttpCookie('cookie_check', 1);
        if(!Yii::app()->session['authScenario']) {
            Yii::app()->session['authScenario'] = 'login';
        }
        if(Yii::app()->session['authModel']) {
            $oAuthModel = Yii::app()->session['authModel'];
        } else {
            $oAuthModel = new AuthModel(Yii::app()->session['authScenario']);
        }
        $sResult = Yii::app()->session['authResult'];
        $this->render('auth', array("user" => $oAuthModel, "error" => $sResult));
	}

    public function actions() {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xEBF4FB,
            ),
        );
    }

    /**
     * Проверка введенных пользователем логина/пароля или проверочного кода
     * Функция принимает исходные данные в POST (из формы на сайте) и в зависимости от типа пришедших данных
     * производит проверку на корректность введенной пары логин-пароль или проверочного кода, отправленного на мобильный телефон.
     * Работа происходит через Ajax, результат возвращается в браузер в формате JSON. Выводится либо сообщение об ошибке,
     * либо логическое (bool) TRUE, когда ошибок не возникает.
     */
    public function actionCheck() {
        $arUserData = Yii::app()->request->getPost('AuthModel');
        $sEmail = trim($arUserData['email']);
        $sPassword = trim($arUserData['password']);
        $sIp = $this->get_user_ip();

        // Логгируем попытку входа
        $oLoginAttemptsModel = new SpLoginAttempts();
        $arLoginData = array(
            "email" => $sEmail,
            "referrer" => Yii::app()->getBaseUrl(true),
            "ip" => $sIp,
            //"date_create" => date("Y-m-d H:i:s")
        );

        $oAuthModel = new AuthModel();
        if($this->isCaptchaNeed($oLoginAttemptsModel, $arUserData)) {
            $oAuthModel->scenario = 'withCaptcha';
        } else {
            $oAuthModel->scenario = 'login';
        }

        // Проверяем корректность пары логин-пароль
        if (!empty($arUserData)) {
            $oAuthModel->attributes = $arUserData;
            // Проверяем правильность данных
            if(Yii::app()->session['authScenario'] == 'login' && $oAuthModel->scenario == 'withCaptcha') {
                $sResult = 'Введите проверочный код';
                Yii::app()->session['authScenario'] = 'withCaptcha';
            } else {
                if($oAuthModel->validate()) {
                    // если всё ок
                    $arLoginData['success'] = 1;
                } else {
                    //print_r($oAuthModel->getErrors());
                    $arResult = $oAuthModel->getErrors();
                    $sResult = "";
                    foreach($arResult as $key=>$value) {
                        $sResult .= $value[0] . '<br />';
                    }
                    $arLoginData['success'] = 0;
                    $arLoginData['error'] = $sResult;

                    // Проверяем необходимость в показе капчи
                    if(Yii::app()->request->isAjaxRequest) {
                        echo json_encode(array("msg" => 'error|' . $sResult));
                    }
                }
            }
        }

        $oLoginAttemptsModel->attributes = $arLoginData;
        $oLoginAttemptsModel->save();

        if($arLoginData['success'] == 1) {
            unset(Yii::app()->session['authScenario']);
            unset(Yii::app()->session['authResult']);
            unset(Yii::app()->session['authModel']);
            $this->redirect(Yii::app()->getBaseUrl(true) . '/admin');
        } else {
            Yii::app()->session['authResult'] = $sResult;
            Yii::app()->session['authModel'] = $oAuthModel;
            $this->redirect(Yii::app()->getBaseUrl(true) . '/auth');
        }
        //$this->render('auth', array("user" => $oAuthModel, "error" => $sResult));

        // Проверяем является ли пользователь гостем
        // ведь если он уже зарегистрирован - формы он не должен увидеть.
        /*if (!Yii::app()->user->isGuest) {
            echo true;
        } else {*/

        //}
    }

    /**
     * Проверка необходимости капчи
     */
    private function isCaptchaNeed($oLoginAttemptsModel, $arUserData) {
        $criteria= new CDbCriteria();
        $criteria->compare('success', 0);
        $criteria->compare('ip', $arUserData['ip']);
        $criteria->compare('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_create)', '< ' . Yii::app()->params['loginAttemptsCheckPeriod'] * 60);
        $criteria->compare('email', $arUserData['email']);
        $iLoginAtemptsCount = $oLoginAttemptsModel->count($criteria);

        $criteria= new CDbCriteria();
        $criteria->compare('success', 1);
        $criteria->compare('ip', $arUserData['ip']);
        $criteria->compare('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_create)', '< ' . Yii::app()->params['loginAttemptsCheckPeriod'] * 60);
        $criteria->compare('email', $arUserData['email']);
        $iSuccessLoginAtemptsCount = $oLoginAttemptsModel->count($criteria);
        if($iLoginAtemptsCount < Yii::app()->params['loginAttempts'] || $iSuccessLoginAtemptsCount > 0) {
            return false;
        } else {
            return true;
        }
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