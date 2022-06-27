<?php

class SiteController extends Controller
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
	
	public function filters()
	{
	    return array(
	        
	        'CheckBrouser +page',
	        //'SetTheme +list, info',
	        //'accessControl', // perform access control for CRUD operations
	      
	    );
	}

	
	
	private $min_chunk_size = 10000;
	private $chunks_path = "E:\smotr_file";
	private $wstip = "10.10.0.16";
	private $wstport = "8081";

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

	public function actionGetconnections(){
		
		
		//Тут смотрим текущие соединения
		$criteria=new CDbCriteria;
		//$criteria->condition=" message_id = :message_id";
		//$criteria->params = array(':message_id'=>$message);
		//$criteria->order = "t.id";
		$models = Connections::model()->findAll($criteria);
		
		
		
		$this->renderPartial('connectionlist', array('models'=>@$models));
	}
	
	///
	
	
	public function actionKostino(){
	
		$id = Yii::app()->getRequest()->getParam('id', 2);
		
	 echo 'echo in action<br>';
	 $this->render('kostino'.$id);
	}
	
	public function actionUpload(){
		
		
		
		$Payload=Yii::app()->getRequest()->getParam('Payload', NULL);
		if($Payload!=NULL && isset($Payload['image_uploaded'])) $image_uploaded = $Payload['image_uploaded'];
		else $image_uploaded = NULL;
		
		if($Payload!=NULL && isset($Payload['chunks_num'])) $chunks_num = $Payload['chunks_num']; 
		else $chunks_num = NULL;
		
		if($image_uploaded==NULL)$payloadform = new Payload('notloaded');
		else {
			$payloadform = new Payload('loaded');
			$payloadform->image_uploaded = $image_uploaded;
			
			if($chunks_num!=NULL) $payloadform->chunks_num = $chunks_num;
			
			////Получаем параметры изображения
			$img = $_SERVER['DOCUMENT_ROOT'].$image_uploaded;
			$img_size = filesize ($img);
			//$payloadform->image = $img_params;
			
			if($img_size>$this->min_chunk_size){
				$max_chunks = round($img_size/$this->min_chunk_size,0); ///////округляется до большего
				//echo $max_chunks;
			}
			else $max_chunks = 1;
			
		}
		$payloadform->transfered = 0;
		
		if(isset($_POST['Payload']))
		{
			

			
			$payloadform->setAttributes($_POST['Payload'], false);
			$im = CUploadedFile::getInstance($payloadform,'image');
			$payloadform->image = $im;

			if($payloadform->validate()){
				
				if($payloadform->scenario=='notloaded'){
					
					
					$tm = time();
					$path = $_SERVER['DOCUMENT_ROOT'].'/temp/'.$tm;
					if($payloadform->image->saveAs($path)){
						$payloadform->image_uploaded= '/temp/'.$tm;
						
						
						
						////Получаем параметры изображения
						$img = $_SERVER['DOCUMENT_ROOT'].$payloadform->image_uploaded;
						$img_size = filesize ($img);
						//$payloadform->image = $img_params;
							
						if($img_size>$this->min_chunk_size){
							$max_chunks = round($img_size/$this->min_chunk_size,0); ///////округляется до большего
							//echo $max_chunks;
						}
						else $max_chunks = 1;
						
					}
					
					
					
					
					
				}
				elseif($payloadform->scenario=='loaded'){////Тут разбиваем на число частей файл, пишем его кусками, заносим в БД и удаляем исходный
				    $addzerros = false;
				    if (isset($_POST['Payload']['addzerros'])) {
				        //$addzerros = TRUE;
				        if($_POST['Payload']['addzerros']=='1') $addzerros=true;
				        //if()
				    }
			
				    
				    $this->saveChunks($payloadform->image_uploaded, $chunks_num, $payloadform, $addzerros);
					$payloadform->image_uploaded = NULL;
				}
			}
			else{

			}
		}
		
		$station = CHtml::listData(Station::model()->findAll(array('order' => 'name')), 'id', 'name');
		$ka = CHtml::listData(Ka::model()->findAll(array('order' => 'name')), 'id', 'name');
		
		$params = array('payloadform'=>$payloadform, 'station'=>$station, 'ka'=>$ka);
		if(isset($max_chunks))$params['max_chunks'] = $max_chunks;
		if(isset($img_size)) $params['img_size']=$img_size;
		
		$this->render('uploadform', $params);
	}
	
	
	private function saveChunks($file_name, $chunks_num, Payload $payloadform, $addzerros){
	    
	    
		$img = $_SERVER['DOCUMENT_ROOT'].$file_name;
		$img_size = filesize ($img);
		//$img_size = 7;
		$pf = new PayloadFile();
		$pf->size =$img_size;
		$pf->save(false);
		
		
		//echo '$pf->id'.$pf->id.'<br>';
		echo '$chunks_num: '.$chunks_num.'<br>';
		echo '$img_size: '.$img_size.'<br>';
		$chunk_size = $img_size/$chunks_num;
		$chunk_size_rounded = round($img_size/$chunks_num,0);
		
		echo '$chunk_size: '.$chunk_size.'<br>';
		echo '$chunk_size_rounded: '.$chunk_size_rounded.'<br>';
		
		//if($chunk_size_rounded*$chunks_num<$img_size ) ;//$chunk_size = $chunk_size_rounded+1;
		//elseif($chunk_size_rounded*$chunks_num>$img_size )$chunk_size = $chunk_size_rounded;
		$chunk_size = $chunk_size_rounded;
		
		echo '$chunk_size: '.$chunk_size.'<br>';
		
		
		for($k=1; $k<=$chunks_num; $k++){
			if($k==$chunks_num){////////////////последний
			    $sizes[]=array('start'=>($k-1)*$chunk_size, 'end'=>$img_size-1, 'size'=>$img_size-($k-1)*$chunk_size);
			    
			}
			else {///////////первый и до предпоследнего
			    $sizes[]=array('start'=>($k-1)*$chunk_size, 'end'=>$k*$chunk_size-1, 'size'=>($k*$chunk_size)-(($k-1)*$chunk_size));
			}
		}
		/////////Получили указатели на части файла
		
		var_dump($addzerros);
		//$addzerros=false;
		
		echo '<pre>';
		print_r($sizes);
		echo '</pre>';
		
		

		
		
		$handle = fopen($img, "r");
		$contents = fread($handle, $img_size);
		fclose($handle);
		
		////////Получаем части
		$parts = array();
		$part = 0;

		foreach ($sizes as $cur){
			
			$b = $cur['start'];
			$e = $cur['end'];
			$s = $cur['size'];
			
			echo "$b=>$e=>$s<br>";
			
			for($i=$b; $i<=$e; $i++){
				@$parts[$part].=$contents[$i];
			}
			
			if($addzerros==true){ //////////////добиваем нули в куски
			    if($s<$chunk_size){
			        //echo 'sdfsdfsd';
			        for ($ii=1; $ii<=100; $ii++){
			            @$parts[$part].= pack('H*', base_convert('', 2, 16));
			        }
			    }
			}
			
			print_r(strlen(@$parts[$part])).'<br>';
	        		
			$part++;
			
			
			
			
		}
		
		//exit();
		
		////////////Непосредственно сохраняем ссылки в БД и на диске
		if(empty($parts)==false && count($parts)>0){
			foreach($parts as $chunk){
				$payload = new Payload();
				$payload->station_id = $payloadform->station_id;
				$payload->ka_id = $payloadform->ka_id;
				$payload->file_id=$pf->id;
				$payload->bin = $chunk;
				if($payload->save()){
					$img = $this->chunks_path."\\".$payload->id;
					$handle = fopen($img, "w");
					$contents = fwrite($handle, $chunk);
					fclose($handle);
				}
			}
		}
	}
	
	
	/**
	 * Сюда будет приходить список ид кусков из которых нужно сформировать картинку, открыв новое окно
	 */
	public function actionCompose(){
		$ids=Yii::app()->getRequest()->getParam('ids', NULL);
		$type=Yii::app()->getRequest()->getParam('type', NULL);
		if($ids==NULL || trim($ids)=='') exit;
		$arids = array_filter(explode(',',$ids));
		//print_r($arids);
		
		if($type=='file') $src=Yii::app()->createUrl('site/buildimage', array('arids'=>serialize($arids)));
		else $src=Yii::app()->createUrl('site/buildimagedb', array('arids'=>serialize($arids)));
		
		$this->renderPartial('composed', array('src'=>$src));
	}
	
	public function actionBuildimagedb(){
	    
		header('Content-Type: image/png');
		$ids=Yii::app()->getRequest()->getParam('arids', NULL);
		$ids = unserialize($ids);
		if(is_array($ids) AND empty($ids)==false){
				
			$contents = "";
				
			$criteria=new CDbCriteria;
			$criteria->condition = " t.id IN (".implode(',', $ids).")";
			$criteria->order="t.id";
			$models=Payload::model()->findAll($criteria);
			for($i=0, $c=count($models); $i<$c; $i++ ){
		
				
				$contents.= $models[$i]->bin;
				
				$models[$i]->transfered = 1;
				$models[$i]->save();
		
			}
				
		
			echo $contents;
		}
		exit;
	}
	
	public function actionBuildimage(){
		header('Content-Type: image/png');
		$ids=Yii::app()->getRequest()->getParam('arids', NULL);
		
		//echo 'wewre';
		$ids = unserialize($ids);
		if(is_array($ids) AND empty($ids)==false){
			
			$contents = "";
			
			$criteria=new CDbCriteria;
			$criteria->condition = " t.id IN (".implode(',', $ids).")";
			$criteria->order="t.id";
			$models=Payload::model()->findAll($criteria);
			for($i=0, $c=count($models); $i<$c; $i++ ){
				
				$img = $this->chunks_path.'/'.$models[$i]->id;
				$handle = fopen($img, "r");
				$contents.= fread($handle, 5000000);
				fclose($handle);
				$models[$i]->transfered = 1;
				$models[$i]->save();
				
			}
			

			echo $contents;
		}
		exit;
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
	
	
	public function actionLoopsocket(){
		$this->render('loopsocket');
	}
	
	
	
	public function actionRequestregister() { ///////////Форма а ля запроса регистрации
		//$this->layout = 'main_index';
		$contact=new RegisterrequestForm;
		if(isset($_POST['RegisterrequestForm']))
		{
	
			$contact->attributes=$_POST['RegisterrequestForm'];
			if($contact->validate())
			{
				//$headers="From: {$contact->email}\r\nReply-To: {$contact->email}";
				$headers="From: ".$contact->email."\r\n";
				$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
				$message=$this->renderPartial('registerrequest/mailtext', array('contact'=>$contact), true);
				$possible_ext=array('doc', 'docx', 'rtf', 'pdf');
					
				foreach($possible_ext as $extension) {
					$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/'.md5(Yii::app()->session->sessionId).'.'.$extension;
					if(file_exists($new_file_name) AND is_file($new_file_name))	 {
							
						$atchname =  $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/attachment.'.$extension;
							
						@rename($new_file_name, $atchname);
							
						$filename = $atchname;
						break;
					}
				}
					
				if(isset($filename)) $qqq = FHtml::XMail( Yii::app()->params['infoEmail'], Yii::app()->params['jobEmail'], 'Заявка на регистрацию', $message, $filename);
				else @mail(Yii::app()->params['adminEmail'], iconv( "UTF-8", "CP1251",'Заявка на регистрацию'), iconv( "UTF-8", "CP1251", $message),$headers);
					
					
				@unlink($filename);
				Yii::app()->user->setFlash('register_request','Ваша заявка отправлена.');
				$contact=new RegisterrequestForm;////////Обнуляем
				$this->redirect(Yii::app()->createUrl('site/requestregister'));
				exit();
				//$this->refresh();
			}
		}
			
			
			
			
	
		$params=array();
		$params['contact']=$contact;
		$this->render('registerrequest', $params);
	}////////////public function actionCooperation() {
	
	

	///////////Метод поиска фоток в базе по полученному экстенту (координатам выбранной области)
	public function actionSearchArhive(){
		//	echo '111';
	    $user_id = 1;////TODO пока, потом нужно будет смотреть какой залогинился
	    
	    $user_cart_files =  array();
	    ///////смотрим есть ли у пользователя файлы в корзине
	    $criteria=new CDbCriteria;
	    $criteria->condition = "t.user_id=:user_id";
	    $criteria->params = array(':user_id'=>$user_id);
	    //$criteria->order="t.id";
	    $cart_files=Cart::model()->findAll($criteria);
	    if($cart_files!=null){
	        ///как сделать одномерный массив ?
	        foreach ($cart_files as $cf){
	            $user_cart_files[$cf->file_id]=$cf->cost;
	        }
	    }
	    
		$extent = Yii::app()->getRequest()->getParam('extent', NULL);
		//print_r($extent);
		$criteria=new CDbCriteria;
		//$criteria->condition = " t.id IN (".implode(',', $ids).")";
		$criteria->order="t.id";
		$models=testimages::model()->findAll($criteria);
		for($i=0, $c=count($models); $i<$c; $i++ ){
			$extents[]=array(
					'extent'=>$models[$i]->coordinates,
					'geometry'=>$models[$i]->geometry,
					'filename'=>trim($models[$i]->filename),
					'shot_dt'=>$models[$i]->shot_dt,
					'file_id'=>$models[$i]->id,
					'legend'=>$models[$i]->legend,
			        'incart'=>isset($user_cart_files[$models[$i]->id]),
			);
		}
		if(is_array($extents) && empty($extents)==false){
			//echo '<pre>';
			//print_r($extents);
			//echo '</pre>';
			
			for ($i=count($extents)-1; $i>=0; $i-- ) {
				$current_extent = str_replace('(', '', $extents[$i]['extent']);
				$current_extent = str_replace(')', '', $current_extent);
				$points=explode(',', $current_extent);
				$points_final[0]=$points[2];
				$points_final[1]=$points[3];
				$points_final[2]=$points[0];
				$points_final[3]=$points[1];
				$pf[$i]['extent'] = $points_final;
				$pf[$i]['filename'] = $extents[$i]['filename'];
				//$pf[$i]['shot_dt'] = date('d.M.Y H:s:i', $extents[$i]['shot_dt']);
				$pf[$i]['shot_dt'] = $extents[$i]['shot_dt'];
				$pf[$i]['file_id'] = $extents[$i]['file_id'];
				$pf[$i]['legend'] = $extents[$i]['legend'];
				$pf[$i]['incart'] = $extents[$i]['incart'];
				$pf[$i]['imgpath'] = urlencode('images\\'. $extents[$i]['filename']);
				
			}
			//echo '<pre>';
			//print_r($pf);
			//echo '</pre>';
			
			echo json_encode($pf);
			
			//$this->renderPartial('geojson', array('models'=>$models));
		}
		
		
	}
	
	/// метод для приема команд для управления websocket сервером. Делаем алиас monitoring/state
	public function actionMonitoringState(){
	    // Allow from any origin
	    /*
	    echo '<pre>';
	    print_r($_SERVER);
	    echo '</pre>';
	    */
	    if (isset($_SERVER['HTTP_ORIGIN'])) {
	        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
	        // you want to allow, and if so:
	        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	        header('Access-Control-Allow-Credentials: true');
	        header('Access-Control-Max-Age: 86400');    // cache for 1 day
	    }
	    
	    // Access-Control headers are received during OPTIONS requests
	    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	        
	        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
	            // may also be using PUT, PATCH, HEAD etc
	            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	            
	            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
	                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	                
	                exit(0);
	    }
	    
	    
	    $key = "example_key";
	    $key1 = "exdsfsdfample_key";
	    $allheaders = getallheaders();
	   // $id = Yii::app()->getRequest()->getParam('id',null);
	    $method = Yii::app()->getRequest()->getRequestType() ;
	    
	    //print_r($allheaders);
	    
	    try {
	       // if($allheaders && isset($allheaders['authorization'])){
	            $jsondata = Yii::app()->request->getRawBody();
	            if($jsondata!=null) $devicedata = json_decode($jsondata);
	            if($devicedata!=null){
	                if(isset($devicedata->state) ){
	                        ///////////////////Пробуем начать мониторинг сервера
	                           
	                            $wsc = new websocketclient();
	                            if( $wsc->websocket_open($this->wstip, $this->wstport) ) {
	                                $wsc->websocket_write($jsondata);
	                                //echo "Server responed with: " . $wsc->websocket_read($errstr);
	                                echo json_decode($wsc->websocket_read($errstr));
	                                exit();
	                            }
	                }
	            }
	           
	        //}

	        
	    } catch (Exception $e) {
	        print_r($e);
	    }
	    
	    exit();
	}
	
	public function actionCyclogramapi(){
	    if (isset($_SERVER['HTTP_ORIGIN'])) {
	        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
	        // you want to allow, and if so:
	        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	        header('Access-Control-Allow-Credentials: true');
	        header('Access-Control-Max-Age: 86400');    // cache for 1 day
	    }
	    // Access-Control headers are received during OPTIONS requests
	    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	            
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            
            exit(0);
	    }
	    $key = "example_key";
	    $key1 = "exdsfsdfample_key";
	    $allheaders = getallheaders();
	    
	    $wstip = $this->wstip; //"10.10.0.16";
	    $wstport = $this->wstport; //8082;
	    
	    // $id = Yii::app()->getRequest()->getParam('id',null);
	    $method = Yii::app()->getRequest()->getRequestType() ;
        try {
            $jsondata = Yii::app()->request->getRawBody();
            if($jsondata!=null) $devicedata = json_decode($jsondata);
            //print_r($devicedata);
            if(isset($devicedata->cyclogram) || isset($devicedata->command) ){
                
                
               
                $pdo_conn=new PDO(Yii::app()->db->connectionString, Yii::app()->db->username, Yii::app()->db->password);
                
               // echo 'qweqwe';
                //exit();
                

                if(isset($devicedata->command->executionType) && $devicedata->command->executionType=='saveCommand'){
                    //////////Тут нужно сохранить комманду, но т.к. циклограммы хранятся в динамически создаваемых таблицах, работаем напрямую с базой
                    /*
                    echo '<pre>';
                    print_r($devicedata->command);
                    echo '</pre>';
                    exit();
                    */
                    $equipment = J400Equipment::model()->findAll();
                    foreach ($equipment as $dev) {
                        $equipmentbynum[$dev->LocalNum]=array('nType'=>$dev->nType, 'nMarka'=>$dev->nMarka);
                    }
                    try {
                        $sql="UPDATE ".$devicedata->command->cyclogram_name." SET CommandNum = ".$devicedata->command->command->CommandNum.", 
                        UnitNum = ".$devicedata->command->command->UnitNum.", 
                        Params = '".$devicedata->command->command->Params."', 
                        CountPar =  ".(count(explode(';',$devicedata->command->command->Params))-1).", 
                        UnitMark =  ".$equipmentbynum[$devicedata->command->command->UnitNum]['nMarka'].", 
                        UnitType =  ".$equipmentbynum[$devicedata->command->command->UnitNum]['nType']." 
                        WHERE NumInCicl = ".$devicedata->command->command->NumInCicl;
                        //echo $sql;
                        //exit();
                        if ($pdo_conn instanceof PDO) {
                            try {
                                $rez = $pdo_conn->query($sql);
                            } catch (PDOException $e) {
                                print_r($e);
                            }
                            if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
                                foreach ($rez as $row) {
                                    echo 'line:661';
                                    print_r($row);
                                    echo "\n\r";
                                }
                            }
                        }
                    } catch (PDOException $e) {
                        echo "Failed to get DB handle 1: " . $e->getMessage() . "\n";
                        exit();
                    }
                }
                
                if(isset($devicedata->command->executionType) && $devicedata->command->executionType=='addCommand'){
                   // echo '<pre>';
                    //print_r($devicedata->command);
                    //echo '</pre>';
                    
                    $sql = "INSERT INTO ".$devicedata->command->cyclogram_name." (	CommandNum, UnitMark, 	UnitType, UnitNum, DstLvs, DstAb, TimeOut, CountPar, Params) 
                            VALUES(0,21,21,0,1,1, 10000, 0, '')";
                    if ($pdo_conn instanceof PDO) {
                        try {
                            echo $sql;
                            $rez = $pdo_conn->query($sql);
                        } catch (PDOException $e) {
                            print_r($e);
                            echo 'line:685';
                        }
                        
                    }
                }
                
                if(isset($devicedata->command->executionType) && $devicedata->command->executionType=='deleteCommand'){
                    /*
                    echo '<pre>';
                    print_r($devicedata->command);
                    echo '</pre>';
                    */
                    //////1. Загружаем циклограмму и смотрим не нестираемая ли эта циклограмма
                    $CYCLOGRAM = J400Ciclogramms::model()->findByPk($devicedata->command->cyclogram_id);
                    if($CYCLOGRAM->READONLY==1){
                        echo "Read only cyclogram";
                        return;
                    }
                    else{
                        /////2. Удаляем команду
                        //echo 'qweqwe';
                        $sql = "DELETE  FROM ".$devicedata->command->cyclogram_name." WHERE CommandNum=".$devicedata->command->command->CommandNum." AND
                        NumInCicl = ".$devicedata->command->command->NumInCicl;
                        if ($pdo_conn instanceof PDO) {
                            try {
                                echo $sql;
                                $rez = $pdo_conn->query($sql);
                            } catch (PDOException $e) {
                                print_r($e);
                                echo 'line:714';
                            }

                        }
                    }
                }
                
                if(isset($devicedata->command->executionType) && $devicedata->command->executionType=='copyCyclogram'){
                    //print_r($devicedata->command);
                    $source = J400Ciclogramms::model()->findByPk($devicedata->command->cyclogram_id);
                    //print_r($source);
                    if($source!=null){
                        $target = new J400Ciclogramms();
                        $taget_name = $source->CKG_Name.'_copy';
                        $target->CKG_Name = $taget_name;
                        $target->CKG_Description = $source->CKG_Description.' (copy)';
                        $target->READONLY = 0;
                        try {
                            $target->save(false);
                            //echo 'werwer';
                            $this->copyCyclogramContent($pdo_conn, $source->CKG_Name, $taget_name);
                        } catch (Exception $e) {
                            print_r($e);
                            exit();
                        }
                    }
                }
                if(isset($devicedata->command->executionType) && $devicedata->command->executionType=='deleteCyclogram'){
                    $source = J400Ciclogramms::model()->findByPk($devicedata->command->cyclogram_id);
                    if($source!=null){
                        if($source->READONLY<>1){
                            $taget_name = $source->CKG_Name;
                            $this->deleteCyclogramContent($pdo_conn,  $taget_name);
                            $source->delete();
                        }
                    }
                }
                
                if(isset($devicedata->command->executionType) && $devicedata->command->executionType=='renameCyclogram'){
                    $source = J400Ciclogramms::model()->findByPk($devicedata->command->cyclogram_id);
                    if($source!=null){
                        if($source->READONLY<>1){
                            $taget_name = $source->CKG_Name;
                            $new_name = $devicedata->command->newname;
                            $this->renameCyclogramContent($pdo_conn,  $taget_name, $new_name);
                            $source->CKG_Name = $new_name;
                            $source->save();

                        }
                    }
                }
                

                $wsc = new websocketclient();
                if( $wsc->websocket_open($wstip, $wstport) ) {
                    //var_dump($jsondata);
                    //var_dump($wsc);
                    $wsc->websocket_write($jsondata);
                    $answ = json_decode($wsc->websocket_read($errstr));
                   // echo $answ;
                    //if($answ=='opening')  time_nanosleep(0, 400000000);
                    echo json_encode($answ);
                    
                    //echo "Server responed with: " . $wsc->websocket_read($errstr);
                    exit();
                }
            }

        } catch (Exception $e) {
            print_r($e);
        }
	}
	
	
	
	
	/**Удаление таблицы циклограммы
	 * @param соединение PDO  $pdo_conn
	 * @param Имя удаляемой циклограммы $taget_name
	 */
	private function deleteCyclogramContent($pdo_conn,  $taget_name){
	    $sql = "DROP TABLE ".$taget_name;
	    try {
	        $rez = $pdo_conn->query($sql);
	    } catch (PDOException $e) {
	        print_r($e);
	    }
	}
	
	private function renameCyclogramContent($pdo_conn,  $taget_name, $new_name){
	    $sql = "RENAME TABLE `$taget_name` TO `$new_name` ; ";
	    try {
	        $rez = $pdo_conn->query($sql);
	    } catch (PDOException $e) {
	        print_r($e);
	    }
	}
	
	/**Копирование циклограммы
	 * @param соединение PDO $pdo_conn
	 * @param имя копируемой циклограммы $source_cy_name
	 */
	private function copyCyclogramContent($pdo_conn, $source_cy_name, $taget_name){
	    $sql = "SELECT `NumInCicl`,
                                    `CommandNum`,
                                    `UnitMark`,
                                    `UnitType`,
                                    `UnitNum`,
                                    `DstLvs`,
                                    `DstAb`,
                                    `TimeOut`,
                                    `CountPar`,
                                    `Params`
                                    FROM $source_cy_name ORDER BY 	NumInCicl ASC";
	    //echo $sql.'<br>';
	    if ($pdo_conn instanceof PDO) {
	        try {
	            $rez = $pdo_conn->query($sql);
	        } catch (PDOException $e) {
	            print_r($e);
	        }
	        if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
	            $insert_sql="
CREATE TABLE IF NOT EXISTS `$taget_name` (
  `NumInCicl` int(4) NOT NULL AUTO_INCREMENT,
  `CommandNum` int(3) DEFAULT NULL,
  `UnitMark` int(3) DEFAULT NULL,
  `UnitType` int(3) DEFAULT NULL,
  `UnitNum` int(4) DEFAULT NULL,
  `DstLvs` int(1) DEFAULT NULL,
  `DstAb` int(1) DEFAULT NULL,
  `TimeOut` int(5) DEFAULT NULL,
  `CountPar` int(1) DEFAULT NULL,
  `Params` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`NumInCicl`),
  UNIQUE KEY `NumInCicl` (`NumInCicl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO $taget_name ( 
                                    `CommandNum`,
                                    `UnitMark`,
                                    `UnitType`,
                                    `UnitNum`,
                                    `DstLvs`,
                                    `DstAb`,
                                    `TimeOut`,
                                    `CountPar`,
                                    `Params`) VALUES ";
	            $vsego = $rez->rowCount();
	            $i=1;
	            foreach ($rez as $row) {
	                //print_r($row);
	                //echo "\n\r";
	                $insert_sql.="(".$row['CommandNum'].", ".$row['UnitMark'].", ".$row['UnitType'].", ".$row['UnitNum'].", ".$row['DstLvs'].",";
	                $insert_sql.=$row['DstAb'].", ".$row['TimeOut'].", ".$row['CountPar'].", '".$row['Params']."') ";
	                if($i<$vsego) $insert_sql.=",";
	                if($i==$vsego) $insert_sql.=";";
	                $i++;
	            }
	            echo $insert_sql.'<br>';
	            try {
	                $rez = $pdo_conn->query($insert_sql);
	            } catch (PDOException $e) {
	                print_r($e);
	            }
	        }
	}
	
	}
	
}