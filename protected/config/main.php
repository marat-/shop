<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
$packages = require_once(dirname(__FILE__).'/packages.php');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',
    'language'=>'ru',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'ext.mail.YiiMailMessage',
        'application.modules.srbac.controllers.SBaseController',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'doublepass',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1','192.168.137.1','192.168.10.13'),
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
		),
        'auth' => array(
            'defaultController' => 'Auth',
        ),
        'admin' => array(
            'defaultController' => 'Admin',
        ),
        'news' => array(
            'defaultController' => 'News',
        ),
        'srbac' => array(
            'userclass'=>'Users', //default: User
            'userid'=>'id', //default: userid
            'username'=>'email', //default:username
            'delimeter'=>'@', //default:-
            'debug'=>true, //default :false
            'pageSize'=>10, // default : 15
            'superUser' =>'Authority', //default: Authorizer
            'css'=>'srbac.css', //default: srbac.css
            'layout'=>
            'application.views.layouts.main_admin', //default: application.views.layouts.main,
            'notAuthorizedView'=> 'srbac.views.authitem.unauthorized', // default:
            //srbac.views.authitem.unauthorized, must be an existing alias
            'alwaysAllowed'=>array( //default: array()
                'SiteLogin','SiteLogout','SiteIndex','SiteAdmin',
                'SiteError', 'SiteContact'),
            'userActions'=>array('Show','View','List'), //default: array()
            'listBoxNumberOfLines' => 15, //default : 10 'imagesPath' => 'srbac.images', // default: srbac.images 'imagesPack'=>'noia', //default: noia 'iconText'=>true, // default : false 'header'=>'srbac.views.authitem.header', //default : srbac.views.authitem.header,
            //must be an existing alias 'footer'=>'srbac.views.authitem.footer', //default: srbac.views.authitem.footer,
            //must be an existing alias 'showHeader'=>true, // default: false 'showFooter'=>true, // default: false
            'alwaysAllowedPath'=>'srbac.components', // default: srbac.components
            // must be an existing alias )
        ),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            'loginUrl'=>array('auth'),
		),
        'mail' => array(
            'class' => 'ext.mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
            'connectionString' => 'mysql:host=192.168.20.161;dbname=nefco_site',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'vefllb,04',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
                    'class'=>'CWebLogRoute',
                    'levels'=>'error, warning, profile, trace, info',
                    'showInFireBug' => true,
                    'categories'=>'system.db.CDbCommand.query, application'
				),
                /*array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                array(
                    'class'=>'CProfileLogRoute',
                    'enabled'=>true,
                ),*/
			),
		),
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
        'clientScript' => array(
            'packages' => $packages,
            'class' => 'CClientScript',
            'scriptMap' => array(
                'jquery.js'=>'//code.jquery.com/jquery-1.8.2.min.js',
            ),
            'coreScriptPosition' => CClientScript::POS_HEAD,
        ),
        'image'=>array(
            'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'ImageMagick',
            // ImageMagick setup path
            'params'=>array('directory'=>'/usr/bin'),
        ),
        'request'=>array(
            'enableCsrfValidation'=>false,
        ),
        'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
            'itemTable'=>'AuthItem',
            'assignmentTable'=>'AuthAssignment',
            'itemChildTable'=>'AuthItemChild',
        ),
    ),
    'theme'=>'bootstrap',
    // application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
        'loginAttempts' => 3,
        'loginAttemptsCheckPeriod' => 3,
	),
);