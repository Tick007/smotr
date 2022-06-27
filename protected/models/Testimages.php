<?php

/**
 * This is the model class for table "testimages".
 *
 * The followings are the available columns in table 'testimages':
 * @property string $coordinates
 * @property string $system
 * @property string $id
 */
class Testimages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Testimages the static model class
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
		return 'testimages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('system', 'length', 'max'=>50),
			array('coordinates', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('coordinates, system, id', 'safe', 'on'=>'search'),
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
			'coordinates' => 'Coordinates',
			'system' => 'System',
			'id' => 'ID',
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

		$criteria->compare('coordinates',$this->coordinates,true);
		$criteria->compare('system',$this->system,true);
		$criteria->compare('id',$this->id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}