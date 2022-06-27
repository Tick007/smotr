<?php

/**
 * This is the model class for table "cache_dpd_cities".
 *
 * The followings are the available columns in table 'cache_dpd_cities':
 * @property integer $cityId
 * @property integer $regionCode
 * @property string $cityName
 */
class CacheDpdCities extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CacheDpdCities the static model class
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
		return 'cache_dpd_cities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cityId, regionCode, cityName', 'required'),
			array('cityId, regionCode', 'numerical', 'integerOnly'=>true),
			array('cityName', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cityId, regionCode, cityName', 'safe', 'on'=>'search'),
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
			'cityId' => 'City',
			'regionCode' => 'Region Code',
			'cityName' => 'City Name',
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

		$criteria->compare('cityId',$this->cityId);
		$criteria->compare('regionCode',$this->regionCode);
		$criteria->compare('cityName',$this->cityName,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}