<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $email
 * @property string $name
 * @property string $second_name
 * @property string $surname
 * @property string $password
 * @property string $telephone
 * @property string $address
 * @property string $city
 * @property string $gender_id
 * @property integer $active
 * @property string $description
 */
class Users extends CActiveRecord
{
    public $rememberMe=false;
    public $verifyCode;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, name, password', 'required', 'on'=>'insert'),
			array('active', 'numerical', 'integerOnly'=>true, 'on'=>'insert'),
			array('email, name, second_name, surname, telephone, city', 'length', 'max'=>255, 'on'=>'insert'),
			array('password', 'length', 'max'=>128, 'on'=>'insert'),
			array('address, description', 'length', 'max'=>2048, 'on'=>'insert'),
			array('gender_id', 'length', 'max'=>10, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, name, second_name, surname, password, telephone, address, city, gender_id, active, description', 'safe', 'on'=>'search'),

            array('email, password', 'required', 'on'=>'login, withCaptcha'),
            array('email', 'email', 'on'=>'login, withCaptcha, restore'),
            array('rememberMe', 'boolean', 'on'=>'login, withCaptcha'),
            array('password', 'authenticate', 'on'=>'login, withCaptcha', 'skipOnError'=>'true'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'withCaptcha, restore', 'skipOnError'=>'true'),

            array('email', 'required', 'on'=>'restore'),
            array('email', 'checkAvailable', 'on'=>'restore'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
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
            'rememberMe' => 'Запомнить меня'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('second_name',$this->second_name,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('gender_id',$this->gender_id,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function validatePassword($password)
    {
        return crypt($password,$this->password)===$this->password;
    }

    public function hashPassword($password)
    {
        return crypt($password, $this->generateSalt());
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

    public function authenticate($attribute,$params) {
        // Проверяем корректность пары логин-пароль или смену пароля
        $oIdentity=new UserIdentity($this->email, $this->password);
        if($oIdentity->authenticate()) {
            Yii::app()->user->login($oIdentity);
        }
        else {
            $this->addError('result',$oIdentity->errorMessage);
        }
    }

    public function checkAvailable($attribute,$params) {
        $criteria= new CDbCriteria();
        $criteria->compare('email', $this->email);
        $bEmailExist = $this->count($criteria);
        if(!$bEmailExist) {
            $this->addError($attribute, "Данный email не зарегистрирован в системе.");
        }
    }

    public function beforeSave() {
        $this->date_update = new CDbExpression('NOW()');

        return parent::beforeSave();
    }
}