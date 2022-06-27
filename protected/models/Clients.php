<?php

/**
 * This is the model class for table "clients".
 *
 * The followings are the available columns in table 'clients':
 * @property integer $id
 * @property string $login
 * @property string $first_name
 * @property string $second_name
 * @property string $last_name
 * @property string $client_email
 * @property string $client_password
 * @property string $client_tels
 * @property string $client_post_index
 * @property string $client_country
 * @property string $client_oblast
 * @property string $client_district
 * @property string $client_city
 * @property string $client_street
 * @property string $client_house
 * @property string $client_korpus
 * @property string $client_stroenie
 * @property string $client_apart
 * @property string $client_flore
 * @property string $client_code
 * @property string $client_entrance
 * @property string $client_comments
 * @property integer $client_vip
 * @property string $client_metro
 * @property string $client_fax
 * @property integer $urlico
 * @property integer $type
 * @property integer $parent
 * @property integer $sort_group
 * @property integer $group_belong
 * @property string $client_passport
 * @property integer $status
 * @property string $urlico_txt
 * @property string $last_vizit
 */
class Clients extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Clients the static model class
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
		return 'clients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, client_vip, urlico, type, parent, sort_group, group_belong, status', 'numerical', 'integerOnly'=>true),
			array('login, client_code, client_entrance', 'length', 'max'=>25),
			array('first_name, second_name, last_name, client_email, client_password, client_oblast, client_district, client_city, client_metro, client_fax', 'length', 'max'=>100),
			array('client_tels, client_street', 'length', 'max'=>150),
			array('client_post_index, client_house, client_korpus, client_stroenie, client_apart, client_flore', 'length', 'max'=>10),
			array('client_country, urlico_txt', 'length', 'max'=>50),
			array('client_comments, client_passport', 'length', 'max'=>255),
			array('last_vizit', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, first_name, second_name, last_name, client_email, client_password, client_tels, client_post_index, client_country, client_oblast, client_district, client_city, client_street, client_house, client_korpus, client_stroenie, client_apart, client_flore, client_code, client_entrance, client_comments, client_vip, client_metro, client_fax, urlico, type, parent, sort_group, group_belong, client_passport, status, urlico_txt, last_vizit', 'safe', 'on'=>'search'),
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
			'login' => 'Login',
			'first_name' => 'First Name',
			'second_name' => 'Second Name',
			'last_name' => 'Last Name',
			'client_email' => 'Client Email',
			'client_password' => 'Client Password',
			'client_tels' => 'Client Tels',
			'client_post_index' => 'Client Post Index',
			'client_country' => 'Client Country',
			'client_oblast' => 'Client Oblast',
			'client_district' => 'Client District',
			'client_city' => 'Client City',
			'client_street' => 'Client Street',
			'client_house' => 'Client House',
			'client_korpus' => 'Client Korpus',
			'client_stroenie' => 'Client Stroenie',
			'client_apart' => 'Client Apart',
			'client_flore' => 'Client Flore',
			'client_code' => 'Client Code',
			'client_entrance' => 'Client Entrance',
			'client_comments' => 'Client Comments',
			'client_vip' => 'Client Vip',
			'client_metro' => 'Client Metro',
			'client_fax' => 'Client Fax',
			'urlico' => 'Urlico',
			'type' => 'Type',
			'parent' => 'Parent',
			'sort_group' => 'Sort Group',
			'group_belong' => 'Group Belong',
			'client_passport' => 'Client Passport',
			'status' => 'Status',
			'urlico_txt' => 'Urlico Txt',
			'last_vizit' => 'Last Vizit',
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('second_name',$this->second_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('client_email',$this->client_email,true);
		$criteria->compare('client_password',$this->client_password,true);
		$criteria->compare('client_tels',$this->client_tels,true);
		$criteria->compare('client_post_index',$this->client_post_index,true);
		$criteria->compare('client_country',$this->client_country,true);
		$criteria->compare('client_oblast',$this->client_oblast,true);
		$criteria->compare('client_district',$this->client_district,true);
		$criteria->compare('client_city',$this->client_city,true);
		$criteria->compare('client_street',$this->client_street,true);
		$criteria->compare('client_house',$this->client_house,true);
		$criteria->compare('client_korpus',$this->client_korpus,true);
		$criteria->compare('client_stroenie',$this->client_stroenie,true);
		$criteria->compare('client_apart',$this->client_apart,true);
		$criteria->compare('client_flore',$this->client_flore,true);
		$criteria->compare('client_code',$this->client_code,true);
		$criteria->compare('client_entrance',$this->client_entrance,true);
		$criteria->compare('client_comments',$this->client_comments,true);
		$criteria->compare('client_vip',$this->client_vip);
		$criteria->compare('client_metro',$this->client_metro,true);
		$criteria->compare('client_fax',$this->client_fax,true);
		$criteria->compare('urlico',$this->urlico);
		$criteria->compare('type',$this->type);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('sort_group',$this->sort_group);
		$criteria->compare('group_belong',$this->group_belong);
		$criteria->compare('client_passport',$this->client_passport,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('urlico_txt',$this->urlico_txt,true);
		$criteria->compare('last_vizit',$this->last_vizit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}