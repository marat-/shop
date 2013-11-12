<?php

class AuthModel extends CFormModel {
    public $email;
    public $password;
    public $verifyCode;
    public $rememberMe=false;

    /**
    * Returns the static model of the specified AR class.
    * @param string $className active record class name.
    * @return SysUser the static model class
    */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('email, password', 'required', 'on'=>'login, withCaptcha'),
            array('email', 'email', 'on'=>'login, withCaptcha'),
            array('rememberMe', 'boolean', 'on'=>'login, withCaptcha'),
            array('password', 'authenticate', 'on'=>'login, withCaptcha', 'skipOnError'=>'true'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'withCaptcha, restore', 'skipOnError'=>'true'),
            array('email', 'required', 'on'=>'restore'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'email' => 'Адрес электронной почты',
            'name' => 'Name',
            'second_name' => 'Second Name',
            'surname' => 'Surname',
            'password' => 'Пароль',
            'telephone' => 'Telephone',
            'address' => 'Address',
            'city' => 'City',
            'gender_id' => 'Gender',
            'active' => 'Active',
            'description' => 'Description',
            'verifyCode' => 'Проверочный код',
        );
    }
}