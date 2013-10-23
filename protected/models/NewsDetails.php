<?php

/**
 * This is the model class for table "news_details".
 *
 * The followings are the available columns in table 'news_details':
 * @property integer $id
 * @property integer $news_id
 * @property integer $language_id
 * @property string $header
 * @property string $brief
 * @property string $text
 *
 * The followings are the available model relations:
 * @property News $news
 * @property HbLanguages $language
 */
class NewsDetails extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NewsDetails the static model class
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
		return 'news_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('news_id, language_id, header, brief, text', 'required'),
			array('news_id, language_id', 'numerical', 'integerOnly'=>true),
			array('header', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, news_id, language_id, header, brief, text', 'safe', 'on'=>'search'),
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
			'news' => array(self::BELONGS_TO, 'News', 'news_id'),
			'language' => array(self::BELONGS_TO, 'HbLanguages', 'language_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'news_id' => 'News',
			'language_id' => 'ID языка',
            "header" => 'Заголовок новости',
            "brief" => 'Краткий текст новости',
            "text" => 'Полный текст новости',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('news_id',$this->news_id);
		$criteria->compare('language_id',$this->language_id);
		$criteria->compare('header',$this->header,true);
		$criteria->compare('brief',$this->brief,true);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}