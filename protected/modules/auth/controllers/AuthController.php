<?php

class AuthController extends Controller {
    private $_assetsUrl;
    public $layout = '//layouts/main_bootstrap';

	public function actionIndex() {
        $this->getAssetsUrl();
        $oModel = new AuthModel('login');
        $this->render('auth', array("model" => $oModel));
	}

    public function getAssetsUrl() {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('auth.assets'));
        return $this->_assetsUrl;
    }
}