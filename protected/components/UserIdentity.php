<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;

    public function authenticate()
    {
        $username=strtolower($this->username);
        $user=Users::model()->find('LOWER(email)=?',array($username));
        if($user===null) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
            $this->errorMessage = 'Пользователь не найден!';
        } else if(!$user->validatePassword($this->password)) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
            $this->errorMessage = 'Неверный пароль!';
        } else {
            $this->_id=$user->id;
            $this->username=$user->email;
            $this->setState('title', $user->name);
            $this->errorCode=self::ERROR_NONE;
        }
        return $this->errorCode==self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
}