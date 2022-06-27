<?php

/**
 * This is the model class for table "payment".
 *
 * The followings are the available columns in table 'payment':
 * @property integer $id
 * @property integer $order_id
 * @property integer $shop_id
 * @property string $name
 * @property string $surname
 * @property integer $card_number1
 * @property integer $card_number2
 * @property integer $card_number3
 * @property integer $card_number4
 * @property integer $exp_day
 * @property integer $exp_mounth
 * @property integer $code
 * @property string $trans_date
 * @property double $sum
 * @property string $signature
 * @property integer $status ///////////0 - не оплачен, 1 - оплачен
 * @property datetime $paid_date /////////Дата время когда заказ был оплачен, т.е. когда статус стал 1
 */
class Payment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Payment the static model class
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
		return 'payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
	    //https://www.yiiframework.com/wiki/56/reference-model-rules-validation
		// will receive user inputs.
		return array(
			array('code, order_id, shop_id, sum, signature, name, surname, exp_mounth, exp_day, card_number1, card_number2, card_number3,
 card_number4,  name, surname', 'required'),
			array('order_id, shop_id, card_number1, card_number2, card_number3, card_number4, exp_day, exp_mounth, code', 'numerical', 'integerOnly'=>true),
			array('name, surname', 'length', 'max'=>255),
		    array('card_number1, card_number2, card_number3', 'length', 'max'=>4, 'min'=>4),
		    array('card_number4', 'length', 'max'=>6, 'min'=>4),
		    array('exp_day, exp_mounth', 'length', 'max'=>2),
		    //array('exp_day', 'range','min'=>1,'max'=>31),
		    //array('exp_mounth', 'number','min'=>1,'max'=>12),
		    array('code', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, shop_id, name, surname, card_number1, card_number2, card_number3, card_number4, exp_day, exp_mounth, code, trans_date, sum', 'safe', 'on'=>'search'),
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
		    'merchant'=> array(self::BELONGS_TO, 'PaymentMerchants', 'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order',
			'shop_id' => 'Shop',
			'name' => 'Name',
			'surname' => 'Surname',
			'card_number1' => 'Номер карты',
			'card_number2' => 'Card Number2',
			'card_number3' => 'Card Number3',
			'card_number4' => 'Card Number4',
			'exp_day' => 'Срок действия',
			'exp_mounth' => 'Exp Mounth',
			'code' => 'CVC код',
			'trans_date' => 'Trans Date',
		    'sum' => 'Order sum',
		    'signature'=> 'Order signature',
		    'status' =>'Order status',
		    'paid_date'=>'Order confirmed pay date',
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('card_number1',$this->card_number1);
		$criteria->compare('card_number2',$this->card_number2);
		$criteria->compare('card_number3',$this->card_number3);
		$criteria->compare('card_number4',$this->card_number4);
		$criteria->compare('exp_day',$this->exp_day);
		$criteria->compare('exp_mounth',$this->exp_mounth);
		$criteria->compare('code',$this->code);
		$criteria->compare('trans_date',$this->trans_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}