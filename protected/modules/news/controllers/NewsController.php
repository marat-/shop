<?php

class NewsController extends Controller {
    private $_assetsUrl;
    public $layout = '//layouts/main_bootstrap';

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
                'actions'=>array('create', 'edit', 'delete'),
                'roles'=>array('admin'),
            ),
            array('allow',
                'actions'=>array('show'),
                'users'=>array('*'),
            ),
            array('deny',
                'actions'=>array('create', 'edit', 'delete'),
            ),
        );
    }

	public function actionIndex() {
        $oModel = new NewsModel('login');
        $this->render('news', array("model" => $oModel));
	}

    public function getAssetsUrl() {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('news.assets'));
        return $this->_assetsUrl;
    }

    public function actionShow() {
        $this->layout = '//layouts/main_admin';
        $arNews=new News("search");/*News::model()->findAll(array(
                                            'with'=>array(
                                                'newsDetails'=>array(
                                                    'joinType'=>'INNER JOIN'
                                                ),
                                                'newsGalleries'=>array(
                                                    'joinType'=>'LEFT JOIN'
                                                ),
                                            ),
                                        )
                                    );*/
        //echo "<pre>";print_r($arNews); echo "</pre>";die();
        $arNews->unsetAttributes();
        if (isset($_GET['News'])){
            //Yii::app()->session['News'] = $_GET['News'];
            $arNews->attributes = $_GET['News'];
        }
        $this->render('news', array(
            'gridDataProvider' => $arNews,
        ));
    }
}