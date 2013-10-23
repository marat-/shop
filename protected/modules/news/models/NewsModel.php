<?php

class NewsModel extends CFormModel {
    public $header;
    public $brief;
    public $text;
    public $dateCreate;

    /**
    * Returns the static model of the specified AR class.
    * @param string $className active record class name.
    * @return SysUser the static model class
    */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('header, brief, text, date', 'required', 'on'=>'create'),
        );
    }

    public function attributeLabels() {
        return array(
            "header" => 'Заголовок новости',
            "brief" => 'Краткий текст новости',
            "text" => 'Полный текст новости',
        );
    }
}