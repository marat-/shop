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
            $oAuthModel = new Users(Yii::app()->session['authScenario']);
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

    function actionT() {
        $auth=Yii::app()->authManager;

        /*$auth->createOperation('readPost','просмотр записи');
        $auth->createOperation('updatePost','редактирование записи');
        $auth->createOperation('deletePost','удаление записи');

        $bizRule='return Yii::app()->user->id==$params["post"]->authID;';
        $task=$auth->createTask('updateOwnPost','редактирование своей записи',$bizRule);
        $task->addChild('updatePost');

        $role=$auth->createRole('reader');
        $role->addChild('readPost');

        $role=$auth->createRole('author');
        $role->addChild('reader');
        $role->addChild('createPost');
        $role->addChild('updateOwnPost');

        $role=$auth->createRole('editor');
        $role->addChild('reader');
        $role->addChild('updatePost');

        $role=$auth->createRole('admin');
        $role->addChild('editor');
        $role->addChild('author');
        $role->addChild('deletePost');

        $auth->assign('reader','readerA');
        $auth->assign('author','authorB');
        $auth->assign('editor','editorC');
        $auth->assign('admin','adminD');*/
        $auth->createOperation('adminPanel','доступ к панели администрирования');
        //$role->addChild('readPost');

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
        $sIp = $this->get_user_ip();

        // Логгируем попытку входа
        $oLoginAttemptsModel = new SpLoginAttempts();
        $arLoginData = array(
            "email" => $sEmail,
            "referrer" => Yii::app()->getBaseUrl(true),
            "ip" => $sIp,
        );

        $oAuthModel = new Users();
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
            $this->redirect(Yii::app()->user->returnUrl ? Yii::app()->user->returnUrl : Yii::app()->getBaseUrl(true) . '/admin');
        } else {
            Yii::app()->session['authResult'] = $sResult;
            Yii::app()->session['authModel'] = $oAuthModel;
            $this->redirect(Yii::app()->getBaseUrl(true) . '/auth');
        }
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

    public function actionGenPass($sPassword)
    {
        /*$user = new Users();
        echo (CRYPT_BLOWFISH === 1) ? 'CRYPT_BLOWFISH is enabled!' : 'CRYPT_BLOWFISH is not available';
        var_dump(CRYPT_BLOWFISH);*/
        return crypt($sPassword, $this->generateSalt());
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

    function actionRestore() {
        Yii::app()->request->cookies['cookie_check'] = new CHttpCookie('cookie_check', 1);
        if(!Yii::app()->session['authScenario']) {
            Yii::app()->session['authScenario'] = 'login';
        }
        if(Yii::app()->session['authModel']) {
            $oAuthModel = Yii::app()->session['authModel'];
        } else {
            $oAuthModel = new Users(Yii::app()->session['authScenario']);
        }
        $sResult = Yii::app()->session['authResult'];
        $this->render('password_restore', array("user" => $oAuthModel, "error" => $sResult));
    }

    function actionRestoreCheck() {
        $arRestoreData = Yii::app()->request->getPost('Users');
        $sEmail = trim($arRestoreData['email']);
        // Логируем попытку смены пароля
        $oLoginAttemptsModel = new SpLoginAttempts();
        $arLoginData = array(
            "email" => $sEmail,
            "referrer" => Yii::app()->getBaseUrl(true),
            "ip" => $this->get_user_ip()
        );

        $oAuthModel = new Users('restore');
        $oAuthModel->attributes = $arRestoreData;
        // Проверяем правильность данных
        if($oAuthModel->validate()) {
            $arLoginData['success'] = 1;
            $sPassword = $this->generatePassword();

            $oUsersModel=Users::model()->find(("email = '$oAuthModel->email'"));
            $oUsersModel->password = $this->actionGenPass($sPassword);
            if ($oUsersModel->save()){
                Yii::app()->mail->viewPath = 'application.modules.auth.views.auth';
                $message = new YiiMailMessage;
                $message->view = 'password_changed_letter';
                $message->setBody(array('password'=>$sPassword), 'text/html');
                $message->addTo($oAuthModel->email);
                $message->from = Yii::app()->params['adminEmail'];
                Yii::app()->mail->send($message);
                Yii::app()->user->setFlash('success', "Пароль успешно изменен и выслан на указанный адрес электронной почты!");
            } else {
                Yii::app()->user->setFlash('error', "Произошла ошибка при изменении пароля!");
            }
        } else {
            $arResult = $oAuthModel->getErrors();
            $sResult = "";
            foreach($arResult as $key=>$value) {
                $sResult .= $value[0] . '<br />';
            }
            $arLoginData['success'] = 0;
            $arLoginData['error'] = $sResult;
        }

        $oLoginAttemptsModel->attributes = $arLoginData;
        $oLoginAttemptsModel->save();

        if($arLoginData['success']) {
            unset(Yii::app()->session['authScenario']);
            unset(Yii::app()->session['authResult']);
            unset(Yii::app()->session['authModel']);
            $this->redirect(Yii::app()->getBaseUrl(true) . '/auth');
        } else {
            Yii::app()->session['authResult'] = $sResult;
            Yii::app()->session['authModel'] = $oAuthModel;
            $this->redirect(Yii::app()->getBaseUrl(true) . '/auth/auth/restore');
        }
    }

    /**
     * Генератор пароля
     */
    private function generatePassword() {
        // Символы, которые будут использоваться в пароле.
        $arChars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP!@#$%^&*()-+";
        $arDigits="1234567890";
        // Определяем количество символов
        $iSize=strlen($arChars) - 1;
        $iDigitSize=strlen($arDigits) - 1;
        // Определяем пустую переменную, в которую и будем записывать символы.
        $sPassword = "";
        // Создаём пароль.
        for($i=6;$i>=0;$i--) {
            $sPassword .= !($i%2) ? $arChars[rand(0,$iSize)] : $arDigits[rand(0,$iDigitSize)];
        }
        return $sPassword;
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->getBaseUrl(true) . '/auth');
    }
}