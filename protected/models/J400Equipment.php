<?php

/**
 * This is the model class for table "j400_equipment".
 *
 * The followings are the available columns in table 'j400_equipment':
 * @property string $Id
 * @property integer $nType
 * @property string $nMarka
 * @property integer $LocalNum
 * @property string $NameEq
 * @property integer $NumGroup
 * @property string $NumContrl
 * @property string $NumProcess
 * @property string $Description
 */
class J400Equipment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'j400_equipment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nType, LocalNum, NumGroup', 'numerical', 'integerOnly'=>true),
			array('Id', 'length', 'max'=>4),
			array('nMarka, NumProcess', 'length', 'max'=>6),
			array('NameEq', 'length', 'max'=>63),
			array('NumContrl', 'length', 'max'=>3),
			array('Description', 'length', 'max'=>91),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, nType, nMarka, LocalNum, NameEq, NumGroup, NumContrl, NumProcess, Description', 'safe', 'on'=>'search'),
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
			'Id' => 'ID',
			'nType' => 'N Type',
			'nMarka' => 'N Marka',
			'LocalNum' => 'Local Num',
			'NameEq' => 'Name Eq',
			'NumGroup' => 'Num Group',
			'NumContrl' => 'Num Contrl',
			'NumProcess' => 'Num Process',
			'Description' => 'Description',
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

		$criteria->compare('Id',$this->Id,true);
		$criteria->compare('nType',$this->nType);
		$criteria->compare('nMarka',$this->nMarka,true);
		$criteria->compare('LocalNum',$this->LocalNum);
		$criteria->compare('NameEq',$this->NameEq,true);
		$criteria->compare('NumGroup',$this->NumGroup);
		$criteria->compare('NumContrl',$this->NumContrl,true);
		$criteria->compare('NumProcess',$this->NumProcess,true);
		$criteria->compare('Description',$this->Description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return J400Equipment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
