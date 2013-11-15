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
                'actions'=>array('create', 'getNewsForm','edit', 'delete'),
                'roles'=>array('admin'),
                'users'=>array('*'),
            ),
            array('allow',
                'actions'=>array('show'),
                'users'=>array('reader'),
            ),
            array('deny',
                'actions'=>array('create', 'getNewsForm', 'edit', 'delete'),
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
        $arNews=new News("search");
        $arNews->unsetAttributes();
        if (isset($_GET['News'])){
            //Yii::app()->session['News'] = $_GET['News'];
            $arNews->attributes = $_GET['News'];
        }
        Yii::app()->clientScript->registerCoreScript('yiiactiveform');
        Yii::app()->clientScript->registerCoreScript('bbq');
        if (isset($_GET['ajax']) && $_GET['ajax'] === 'news-grid') {
            $this->renderPartial('news', array(
                'gridDataProvider' => $arNews,
            ), false, true);
        } else {
            $this->showPage($this, 'news', array(
                'gridDataProvider' => $arNews,
                'type'=>'news'
            ));
        }
    }

    public function actionGetNewsForm() {
        if ($_POST['news_id'] != ''){
            $newsId = $_POST['news_id'];
            $oNewsModel = News::model()->findByPk($newsId);
            $oNewsGalleryModel = new NewsGallery('create');
            $oNewsGalleryModel->images = $oNewsGalleryModel->findAll("news_id = " . $oNewsModel->id);
        } else {
            $oNewsModel = new News('create');
            $oNewsDetailsModel = new NewsDetails('create');
            $oNewsGalleryModel = new NewsGallery('create');
        }
        Yii::app()->clientScript->corePackages = array();
        $oHbLanguages = new HbLanguages();
        $arLanguages = $oHbLanguages->findAll();
        foreach($arLanguages as $arLanguage) {
            if($_POST['news_id']) {
                $oNewsDetailsModel = NewsDetails::model()->find("news_id = " . $oNewsModel->id . " AND language_id = " . $arLanguage->id);
            }
            $arNewsDetailsForm[] = array(
                'label'=>$arLanguage->name,
                'content'=>$this->renderPartial('news_form', array("news_model" => $oNewsModel, "news_details_model" => $oNewsDetailsModel, "news_gallery_model" => $oNewsGalleryModel, "language_id" => $arLanguage->id), true, true),
            );
        }
        $arNewsDetailsForm[0]['active'] = true;

        $this->renderPartial('news_form_tabs', array("news_details_form" => $arNewsDetailsForm,"news_model" => $oNewsModel, "news_gallery_model" => $oNewsGalleryModel), false, true);
    }

    public function actionEdit() {
        if($_POST['News']['id']) {
            $oNewsModel=News::model()->findByPk($_POST['News']['id']);
        } else {
            $oNewsModel = new News();
        }

        if (isset($_POST['News'])) {
            $transaction = Yii::app()->db->beginTransaction();
            $oNewsModel->attributes = $_POST['News'];
            if(!$oNewsModel->id) {
                $oNewsModel->date_create = date("Y-m-d H:i:s");
            }

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'news-form') {
                echo CActiveForm::validate(array($oNewsModel));
                Yii::app()->end();
            }

            if ($oNewsModel->save()){
                // Сохраняем детали новости
                NewsDetails::model()->deleteAll(array(
                    'condition'=>'news_id=:news_id',
                    'params'=>array(':news_id'=>$oNewsModel->id),
                ));
                if (isset($_POST['NewsDetails'])){
                    $oHbLanguages = new HbLanguages();
                    $arLanguages = $oHbLanguages->findAll();

                    //$oNewsDetailsModel->attributes = $_POST['NewsDetails'];
                    $iSuccessDetailCount = 0;
                    foreach($arLanguages as $arLanguage) {
                        $oNewsDetailsModel = new NewsDetails();
                        $oNewsDetailsModel->news_id = $oNewsModel->id;
                        $oNewsDetailsModel->language_id = $arLanguage->id;
                        $oNewsDetailsModel->header = $_POST['NewsDetails']['header'][$arLanguage->id];
                        $oNewsDetailsModel->brief = $_POST['NewsDetails']['brief'][$arLanguage->id];
                        $oNewsDetailsModel->text = $_POST['NewsDetails']['text'][$arLanguage->id];
                        if($oNewsDetailsModel->save()) {
                            $iSuccessDetailCount++;
                        }
                    }

                    if ($iSuccessDetailCount){
                        // Сохраняем галерею фото
                        NewsGallery::model()->deleteAll(array(
                            'condition'=>'news_id=:news_id',
                            'params'=>array(':news_id'=>$oNewsModel->id),
                        ));
                        if (isset($_POST['NewsGallery'])){
                            $arImages = $_POST['NewsGallery']['image'];
                            $arError = array();
                            for ($i = 0; $i < sizeof($arImages); $i++){
                                $oNewsGalleryModel = new NewsGallery();
                                $oNewsGalleryModel->news_id = $oNewsModel->id;
                                $oNewsGalleryModel->image = $arImages[$i];
                                $oNewsGalleryModel->save();
                                $arError[$i] = $oNewsGalleryModel->getErrors();
                            }
                        }
                        for ($i = 0; $i < sizeof($arImages); $i++){
                            if(sizeof($arError[$i]) > 0) {
                                $transaction->rollback();
                                echo "error";
                                Yii::app()->end();
                            }
                        }
                        $transaction->commit();
                    } else {
                        $transaction->rollback();
                        echo "error";
                    }
                }
            } else {
                $transaction->rollback();
                echo "error";
            }
            Yii::app()->end();
        }
    }

    public function actionDelete() {
        $iNewsId = $_POST['news_id'];
        $iAction = $_POST['action'];
        if ($iNewsId != ''){
            $oNewsModel=News::model()->findByPk($iNewsId);
            $oNewsModel->del = $iAction;
            $oNewsModel->save();
        }
    }

    /*
     * Обработка загруженных изображений
     */
    public function actionUploadImages() {
        Yii::import("ext.EAjaxUpload.qqFileUploader");

        $folder=Yii::app()->basePath . '/../upload/';// folder for uploaded files
        $allowedExtensions = array("jpg", "gif", "png");//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        $fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
        $fileName=$result['filename'];//GETTING FILE NAME

        $image = Yii::app()->image->load($folder.$fileName);
        $image->resize(300, 300)->quality(100)->sharpen(30);
        $image->save($folder.'small-'.$fileName);

        $image = Yii::app()->image->load($folder.$fileName);
        $image->resize(600, 600)->quality(100)->sharpen(30);
        $image->save($folder.'medium-'.$fileName);

        $image = Yii::app()->image->load($folder.$fileName);
        $image->resize(768, 768)->quality(100)->sharpen(30);
        $image->save($folder.'large-'.$fileName);

        echo $return;// it's array
    }

    public function actionGetNewsView() {
        $newsId = $_POST['news_id'];
        $oNewsModel = News::model()->findByPk($newsId);
        $oNewsDetailsModel = NewsDetails::model()->find("news_id = " . $oNewsModel->id);
        $oNewsGalleryModel = new NewsGallery('create');
        $oNewsGalleryModel->images = $oNewsGalleryModel->findAll("news_id = " . $oNewsModel->id);

        Yii::app()->clientScript->corePackages = array();
        $this->renderPartial('news_view_form', array("news_model" => $oNewsModel, "news_details_model" => $oNewsDetailsModel, "news_gallery_model" => $oNewsGalleryModel), false, true);
    }

    public function renderButtons($data, $row) {
        $arButtonItems = array('label'=>'Действия');
        if(Yii::app()->user->checkAccess('createPost')) {
            $arButtonItems['items'][] =
                array('label'=>'Просмотр', 'url'=>'#', 'linkOptions'=>array(
                    'class' => 'b-news-view',
                )
            );
        }
        $params=array('post'=>$data);
        if(Yii::app()->user->checkAccess('updateOwnPost',$params)) {
                $arButtonItems['items'][] = array('label'=>'Редактирование', 'url'=>'#', 'linkOptions'=>array(
                    'class' => 'b-news-update',
                ),
            );
        }
        if(Yii::app()->user->checkAccess('deletePost')) {
            if($data->del == 0) {
                $arButtonItems['items'][] =  array('label'=>'Удаление', 'url'=>'#', 'linkOptions'=>array(
                    'class' => 'b-news-delete',
                ),
                );
            } else {
                $arButtonItems['items'][] =  array('label'=>'Восстановление', 'url'=>'#', 'linkOptions'=>array(
                    'class' => 'b-news-restore',
                ),
                );
            }
        }
        $this->widget('bootstrap.widgets.TbButtonGroup', array(
            'size'=>'small',
            'type'=>'info',
            'buttons'=> array($arButtonItems),
        ));
    }
}