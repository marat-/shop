<?php
return array(
    /*'jquery' => array(
        'baseUrl' =>  '//code.jquery.com/',
        'js' => array('jquery-1.8.2.min.js', 'jquery-migrate-1.2.1.min.js'),
    ),*/
    'common_plugins' => array(
        'basePath' =>  'webroot.js.common',
        'js' => array('jquery.cookie.js'),
        'depends' => array('jquery'),
    ),
    'common_js' => array(
        'basePath' =>  'webroot.js',
        'js' => array('script_func.js', 'script_init.js', 'script.js'),
        'depends' => array('common_plugins'),
    ),
    'cleditor_js' => array(
        'basePath' =>  'ext.cleditor.assets',
        'js' => array('jquery.cleditor.min.js'),
        'depends' => array('common_plugins'),
    ),
    'common_css' => array(
        'basePath' => 'webroot.css',
        'css' => array('style.css'),
    ),
    'common_resource' => array(
        'depends' => array('common_js', 'common_css'),
    ),
    'news_js' => array(
        'basePath' => 'news.js',
        'js' => array('script.js')
    ),
    'news_css' => array(
        'basePath' => 'news.css',
        'css' => array('news.css'),
    ),
    'news_resource' => array(
        'depends' => array('common_resource', 'cleditor_js', 'news_js', 'news_css'),
    ),
    'auth_js' => array(
        'basePath' => 'auth.js',
        'js' => array('script.js')
    ),
    'auth_css' => array(
        'basePath' => 'auth.css',
        'css' => array('auth.css'),
    ),
    'auth_resource' => array(
        'depends' => array('common_resource', 'auth_js', 'auth_css'),
    ),
);