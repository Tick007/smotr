<?php

/**
 * This is the model class for table "payment_merchants".
 *
 * The followings are the available columns in table 'payment_merchants':
 * @property integer $id
 * @property string $shopname
 * @property string $shopurl
 * @property string $description
 * @property integer $status
 * @property string $login
 * @property string $payment_pass
 * @property string $result_pass
 * @property string $action_success
 * @property string $action_fail
 * @property string $action_result
 */
class PaymentMerchants extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PaymentMerchants the static model class
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
		return 'payment_merchants';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description, status, login, payment_pass, result_pass', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('shopname, shopurl, result_pass, action_success, action_fail, action_result', 'length', 'max'=>255),
			array('description', 'length', 'max'=>512),
			array('login, payment_pass', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shopname, shopurl, description, status, login, payment_pass, result_pass, action_success, action_fail, action_result', 'safe', 'on'=>'search'),
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
			'shopname' => 'Shopname',
			'shopurl' => 'Shopurl',
			'description' => 'Description',
			'status' => 'Status',
			'login' => 'Login',
			'payment_pass' => 'Payment Pass',
			'result_pass' => 'Result Pass',
			'action_success' => 'Action Success',
			'action_fail' => 'Action Fail',
			'action_result' => 'Action Result',
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
		$criteria->compare('shopname',$this->shopname,true);
		$criteria->compare('shopurl',$this->shopurl,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('payment_pass',$this->payment_pass,true);
		$criteria->compare('result_pass',$this->result_pass,true);
		$criteria->compare('action_success',$this->action_success,true);
		$criteria->compare('action_fail',$this->action_fail,true);
		$criteria->compare('action_result',$this->action_result,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}