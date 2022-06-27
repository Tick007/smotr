<?php

class SlovarController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		
		
		
		$this->render('index');
	}


	

	
	public function actionMain(){
		
		
	
		//$ciclograms = $this->getCiclogramList();
		
		$this->render('main');
	}
	



	
	
	
	
	

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{	
				$headers= 'Content-type: text/html; charset=windows-1251' . "\r\n";
				$headers.="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	
	
	
	
	public function actionEspanol()
	{
		$this->layout="nomenu";
		
		
		$model=new SpanishDictForm('qqq');
		if(isset($_POST['SpanishDictForm']))
		{
			$model->attributes=$_POST['SpanishDictForm'];
			if($model->validate())
			{
		
			}
		}
		
		
		
		
		require_once 'excel_reader2.0.php';
		//print_r($attributes);
		$this->layout="nomenu";
		
		//$data = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT'].'/spanish_words.xls');
		$data = new Spreadsheet_Excel_Reader('E:/dropbox/Dropbox/espanol/spanish_words.xls');
		$num_of_rows =$data->rowcount(2) ;
		
		if($model->limit_down==null) $model->limit_down = $num_of_rows;
		if($model->limit_up==null) $model->limit_up = 2;
		
		//echo $num_of_rows.'<br>';
		
		$data->setOutputEncoding('utf-16LE');
		//$data->setUTFEncoder('iconv');
		//$data->setUTFEncoder('mb');
		
		
		for($i=2; $i<=$num_of_rows; $i++) {
			//\\echo $i.': '.$data->val($i, 1).'<br>';
			//echo $this->PRODUCT;
			//echo $data->val($i, 2, 2).': '.$data->val($i, 3, 2).' '.$this->showBytes($data->val($i, 3, 2),'qqq', 1) .'<br>';
			//echo $data->val($i, 2, 2).': '.iconv("UTF-8", "CP1251", $data->val($i, 3, 2)).'<br>';
			$eng = $data->val($i, 2, 2);
			$rus = $this->fixdata($data->val($i, 3, 2));
			$descr = $data->val($i, 4, 2);
			$pod = substr($eng, 0,1);
		
			if(strstr($eng,'>' ) || strstr($eng,'@' ) || strstr($eng,'520' ) || strstr($rus,'=' ) ){
				$eng = $this->fixdata($data->val($i, 2, 2));
				$rus = $data->val($i, 3, 2);
				$pod = substr($eng, 0,1);
		
			}
		
			
			if(trim($rus) && trim($eng) ) {
				
				if($i<=$model->limit_down && $i>=$model->limit_up)$words[] = array('esp'=>$eng,'rus'=>$rus, 'descr'=>$descr,'pod'=>$pod);
				//$i++;
			}
		
		
			
		
			//echo $data->val($i, 2, 2).': '.$data->val($i, 3, 2).'<br>';
		}
		
		
		if($model->word_filter!=null){
			
			$temp_words = $words;
			//print_r($temp_words);
			$words = null;
			//print_r($model->word_filter);
			for($k=0, $c=count($temp_words); $k<$c; $k++){
				//var_dump(strstr($temp_words[$k]['esp'], $model->word_filter));
				
				if(strstr ($temp_words[$k]['esp'], $model->word_filter)!=false || strstr ($temp_words[$k]['rus'], $model->word_filter)!=false) $words[] =$temp_words[$k];
			}
			
		}
		if($words==null) $words[] = array('esp'=>'ha buscado nada','rus'=>'ничего не найдено', 'descr'=>'','pod'=>'');
		
		//print_r($words);
		

		if(Yii::app()->request->isAjaxRequest) $this->renderPartial('content', array('words'=>$words, 'model'=>$model));
		else $this->render('espanol',array('model'=>$model, 'num_of_rows'=>$num_of_rows, 'words'=>$words));
	}
	
	
	
	public function fixdata($string){
	
		$fixed = "";
		$qwerty="";
		$k=0;
		for($i=0;$i<strlen($string);$i=$i+2 ){
			$k=$i/2;
			//$qqq = unpack('s',substr($string.' ', $i, 2));
	
			//$fixed.=$qqq[1].'|';
			$fixed.= iconv('UTF-16LE', 'UTF-8', substr($string.' ', $i, 2));
			//var_dump($qqq);
			/*
			 if(abs($k)==$k){
			$qwerty[]
			}
			*/
	
		}
	
	
	
		return $fixed;
	}
	
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	

	
	

	
}