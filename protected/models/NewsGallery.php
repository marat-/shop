<?php

/**
 * This is the model class for table "news_gallery".
 *
 * The followings are the available columns in table 'news_gallery':
 * @property integer $id
 * @property integer $news_id
 * @property string $image
 *
 * The followings are the available model relations:
 * @property News $news
 */
class NewsGallery extends CActiveRecord
{
    public $images;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NewsGallery the static model class
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
		return 'news_gallery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('news_id, image', 'required'),
			array('news_id', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, news_id, image', 'safe', 'on'=>'search'),
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
			'image' => 'Image',
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
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}