<?php

/**
 * This is the model class for table "j400_ciclogramms".
 *
 * The followings are the available columns in table 'j400_ciclogramms':
 * @property string $CKG_Name
 * @property string $CKG_Description
 * @property string $CKG_Date
 * @property integer $CKG_ID
 * @property integer $ID
 * @property integer $READONLY
 */
class J400Ciclogramms extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'j400_ciclogramms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CKG_Date', 'required'),
			array('CKG_ID, READONLY', 'numerical', 'integerOnly'=>true),
			array('CKG_Name', 'length', 'max'=>41),
			array('CKG_Description', 'length', 'max'=>137),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('CKG_Name, CKG_Description, CKG_Date, CKG_ID, ID, READONLY', 'safe', 'on'=>'search'),
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
			'CKG_Name' => 'Ckg Name',
			'CKG_Description' => 'Ckg Description',
			'CKG_Date' => 'Ckg Date',
			'CKG_ID' => 'Ckg',
			'ID' => 'ID',
			'READONLY' => 'Readonly',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('CKG_Name',$this->CKG_Name,true);
		$criteria->compare('CKG_Description',$this->CKG_Description,true);
		$criteria->compare('CKG_Date',$this->CKG_Date,true);
		$criteria->compare('CKG_ID',$this->CKG_ID);
		$criteria->compare('ID',$this->ID);
		$criteria->compare('READONLY',$this->READONLY);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return J400Ciclogramms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
