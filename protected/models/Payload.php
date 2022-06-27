<?php

/**
 * This is the model class for table "payload".
 *
 * The followings are the available columns in table 'payload':
 * @property integer $id
 * @property integer $station_id
 * @property integer $ka_id
 * @property string $received
 * @property integer $transfered
 */
class Payload extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Payload the static model class
	 */
	
	public $image;////
	public $image_uploaded;////
	public $chunks_num;
	public $addzerros;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payload';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('station_id, ka_id, image', 'required', 'on'=>'notloaded'),
			array('station_id, ka_id, image_uploaded, chunks_num', 'required', 'on'=>'loaded'),
			array('station_id, ka_id, transfered', 'numerical', 'integerOnly'=>true),
			array('image', 'file', 'types'=>'jpg, gif, png', 'on'=>'notloaded'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, station_id, ka_id, received, transfered, file_id', 'safe', 'on'=>'search'),
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
				
				//'kontragent'=> array(self::BELONGS_TO, 'Contr_agents', 'urlico'),
				//'authassignment' => array(self::HAS_ONE, 'Authassignment', 'userid'),
				//'inbox' => array(self::HAS_MANY, 'Message', 'from_user'),
				//'authentications'=>array(self::HAS_MANY, 'Authentications', 'user_id'),
				'station'=>array(self::BELONGS_TO, 'Station', 'station_id'),
				'ka'=>array(self::BELONGS_TO, 'Ka', 'ka_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'station_id' => 'Станция',
			'ka_id' => 'КА',
			'received' => 'Дата получения',
			'transfered' => 'Пепердано',
			'image'=>'Картинка',
			'chunks_num' =>'Разбить на число кусочков',
			'file_id'=>'Идентификатор файла',
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
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('ka_id',$this->ka_id);
		$criteria->compare('received',$this->received,true);
		$criteria->compare('transfered',$this->transfered);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>50,
				),
		));
		/*
		 return new CActiveDataProvider($this, array(
					'criteria'=>$criteria,
					'pagination'=>array(
							'pageSize'=>30,
					),
			));
		 * */
	}
}