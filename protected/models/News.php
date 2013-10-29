<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $date_create
 * @property string $date
 * @property integer $author
 * @property integer $del
 * @property string $image
 *
 * The followings are the available model relations:
 * @property NewsDetails[] $newsDetails
 * @property NewsGallery[] $newsGalleries
 */
class News extends CActiveRecord
{

    public $header;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return News the static model class
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
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_create, date, author', 'required'),
			array('author, del', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date, author, del, image, header', 'safe', 'on'=>'search'),
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
			'newsDetails' => array(self::HAS_MANY, 'NewsDetails', 'news_id'),
			'newsGalleries' => array(self::HAS_MANY, 'NewsGallery', 'news_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date_create' => 'Дата создания записи',
			'date' => 'Дата новости',
			'author' => 'ID Автора',
			'del' => 'Del',
			'image' => 'Image',
            'header' => 'Заголовок новости'
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

        $criteria->together = true;
        $criteria->with=array(
            'newsDetails'=>array(
                'joinType'=>'LEFT JOIN',
                /*'condition' => 'language_id = :languageId',
                'params' => array(':languageId' => 1)*/
            ),
        );

        $criteria->addSearchCondition('newsDetails.header',$this->header,true);

        $criteria->compare('t.id',$this->id);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.date',$this->date,true);
		$criteria->compare('t.author',$this->author);
		$criteria->compare('t.del',$this->del);
		$criteria->compare('t.image',$this->image,true);

        return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.date',
                'attributes'=>array('id','date',)
            ),
            'pagination'=>array(
                'pageSize'=>20,
            ),
		));
	}

    protected function beforeSave () {
        $this->date=date('Y-m-d',strtotime($this->date));
        return parent::beforeSave ();
    }

    protected function afterFind () {
        $this->date=date('d.m.Y',strtotime($this->date));
        return parent::afterFind ();
    }
}