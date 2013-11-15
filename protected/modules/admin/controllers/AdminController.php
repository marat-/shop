<?php

class AdminController extends Controller {
    private $_assetsUrl;
    public $layout='//layouts/main_admin';

    public function beforeAction() {
        $this->getAssetsUrl();
        return true;
    }

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions'=>array('index', 'showNews'),
                'roles'=>array('admin'),
                'users'=>array('*'),
            ),
            array('deny',
                'actions'=>array('index', 'showNews'),
            ),
        );
    }

	public function actionIndex() {
        $oModel = new AdminModel('login');
        $this->showPage($this, 'admin', array("model" => $oModel));
	}

    public function actionShowNews() {
        //print_r(Yii::getPathOfAlias('webroot') . '/js/' ); die();
        list($newsController) = Yii::app()->createController('news/news/show');
        $newsController->actionShow();
    }

    public function getAssetsUrl() {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('admin.assets'));
        return $this->_assetsUrl;
    }
}