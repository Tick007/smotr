<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

    private $md5password;
    
   // function __construct($login, $password){
   //     $this->md5password = md5($password);
   // }
    
	public function authenticate()
	{
	    /*
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		*/
	    //
	    //echo $this->username.'<br>';
	    //echo $this->password.'<br>';
	    
	    
		$this->md5password = md5($this->password);
		$this->errorCode=self::ERROR_NONE;
		$Client = Clients::model()->findByAttributes(array('login'=>$this->username));
		

		
		///////////iz postgre prihodit stroka ravnaya dline polya
		$Client->client_password = trim($Client->client_password);
		
		if($Client!=null && $this->md5password!=''){
		    if($this->md5password != $Client->client_password){/////////Пароль неверен
		        $this->errorCode=self::ERROR_PASSWORD_INVALID;
		    }
		}
		else{///////////Пользователь не найден
		    $this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		
		
		return !$this->errorCode;
		
		/*
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
		*/
	}
}
