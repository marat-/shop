<?php

class AuthModel extends CFormModel {
    public $email;
    public $password;
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
            array('email, password', 'required', 'on'=>'login'),
            array('rememberMe', 'boolean', 'on'=>'login'),
            array('password', 'authenticate', 'on'=>'login'),
        );
    }

    public function authenticate($attribute,$params) {
        $this->_identity=new UserIdentity($this->username,$this->password);
        if(!$this->_identity->authenticate())
            $this->addError('password','Неправильное имя пользователя или пароль.');
    }
}