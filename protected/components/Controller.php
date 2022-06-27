<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	//public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	public $tones = array('3'=>'27777 Гц', '2'=>'3968 Гц', '1'=>'283 Гц', '0'=>'35 Гц');
	var $pageDescription;
	var $pageKeywords;
	var $pageTitle;
	var $leftpanel;
	var $rightpanel;
	var $browser;
	protected $user;
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	
	public function filterCheckBrouser($filterChain){
	    //Yii::import('application.extensions.mbmenu.Browser');
	    //$this->Browser= Browser::detect();
	    $this->browser['Brouser'] = new Browser2();
	    $this->browser['agent']=$this->browser['Brouser']->getBrowser();
	    $this->browser['platform']=$this->browser['Brouser']->getPlatform();
	    $this->browser['isMobile']=(bool)$this->browser['Brouser']->isMobile();
	    $this->browser['agent_details'] =  $this->browser['Brouser']->_agent;
            
	    
	    /*
	     echo '<pre>';
	     print_r($this->browser);
	     echo '</pre>';
	     echo 'Browser = '.$this->browser['Brouser']->getBrowser().'<br>';
	     echo 'Platform = '.$this->browser['Brouser']->getPlatform().'<br>isMobile = ';
	     var_dump($this->browser['Brouser']->isMobile()).'<br>';
	     echo $this->browser['Brouser']->_agent;
	     exit();
	     */
	    
	    /*/////// Note II дефолтный, S 3, Note 3
	     Browser = Android
	     Platform = Android
	     isMobile = bool(true)
	     
	     //////////////////////////Nexus 10
	     Browser = Chrome
	     Platform = Android
	     isMobile = bool(false)
	     
	     /////////////////ipad
	     Browser = iPad
	     Platform = iPad
	     isMobile = bool(true)
	     
	     ////////////////////////Iphone 6
	     Browser = iPhone
	     Platform = iPhone
	     isMobile = bool(true)
	     
	     
	     /////////////////S4
	     Browser = Chrome
	     Platform = Android
	     isMobile = bool(false)
	     
	     ///////////////PC
	     Browser = Chrome / Firefox / Safari /(Опера тоже Chrome)/ Internet Explorer
	     Platform = Windows
	     isMobile = bool(false)
	     
	     /////////////Nokia
	     
	     */
	    
	    $filterChain->run();
	}
	
	public function filterCheckPathSuffix($filterChain){/////////////
				//echo '<br>ewrwer';
					$request = Yii::app()->getRequest();
					$url = $request->getUrl();
					//echo $url.'<br>';
					$last= mb_substr($url, -1, 1);
					if ($last=='/') {
							$this->redirect(mb_substr($url, 0, mb_strlen($url)-1), true, 301);
							exit();
					}
			$filterChain->run();
	}/////////public function filterCheckPathSuffix($filterChain){/////////////
	
	public function filterUserLastUrl($filterChain) { ////////////Ñîõðàíÿåì ññûëêó íà ïîñëåäíþþ ñòðàíèöó ãäå áûë ïîëüçîâàòåëü
			$request = Yii::app()->getRequest();
			$url = $request->getUrl();
			
			//print_r(Yii::app()->user);
			//exit();
			Yii::app()->user->setReturnUrl($url);
			$filterChain->run();
	}/////////ublic function filterUserLastUrl($filterChain) { ////////
	
	public function filterReadKladr($filterChain){ ////////////Ïðîñòî ñìîòðèì òåêóùèé êëàäð
			$cookie=Yii::app()->request->cookies['region'];
				//print_r($cookie);	
					if (isset($cookie) AND trim($cookie->value)<>'') {
							$this->region=$cookie->value;
							$this->global_kladr = Ma_kladr::model()->findByPk($this->region);		
							//echo $this->region_word;
					}///////if (isset($cookie) AND trim($cookie->value)<>'') {
					else 	if(isset(Yii::app()->params['default_kladr_id'])) {
						$this->global_kladr = Ma_kladr::model()->findByPk(Yii::app()->params['default_kladr_id']);	
					}/////////////
			$filterChain->run();		
	}
	
	 public function Add_To_Cart() {
		 
		 //print_r($_GET);
		 //print_r($_POST);
		// exit();
		 
		 
		$add_to_basket = Yii::app()->getRequest()->getParam('add_to_basket');///
		$num_to_basket = Yii::app()->getRequest()->getParam('num_to_basket', 1);//
		 
		 
	 		if (isset($add_to_basket) AND is_numeric($add_to_basket)) {//////////////////Äîáàâëåíèå â êîðçèíó
			
					$tovar_id=intval(trim($add_to_basket));
					$MyBasket = new MyShoppingCart($tovar_id, $num_to_basket  );
			}
			
			$cookie=Yii::app()->request->cookies['YiiCart'];
			if (isset($cookie)){
			 $value=$cookie->value;
				//echo "Ñåé÷àñ óñòàíîâëåííûå êóêè ".$value."/<br>";
	 		}
			//else echo "Íåò êóêè<br>";
		}
		
		public function actions()
		{
			return array(
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha'=>array(
					'class'=>'CCaptchaAction',
					'backColor'=>0xFFFFFF, //öâåò ôîíà êàï÷è
					'testLimit'=>2, //ñêîëüêî ðàç êàï÷à íå ìåíÿåòñÿ
					'transparent'=>false,
					'foreColor'=>0x7a7a7a, //öâåò ñèìâîëîâ
				//	'width'=>'150px',
				 	'height'=>'50px',
				),
			);
		}
		
		
		public function CalculateCartContents(){ //////Ñ÷èòàåì ñîäåðæèìîå êîðçèíû
				 $cook=Yii::app()->request->cookies['YiiCart'];
				if (isset($cook)) {
				$cookie = $cook->value;
					if (isset($cookie)) {/////////////////////////////
					//print_r($cookie);
							$goods=explode('#', $cookie);
							foreach($goods as $product) {
								$sum_num=explode(":", $product);
								if(isset($sum_num[0]) AND isset($sum_num[1])) {
									$products_arr['ids'][]=$sum_num[0];
									$products_arr['num'][$sum_num[0]]=$sum_num[1];
								}
								
								//print_r($sum_num);
							}
					//print_r($products_arr);	
					
					if (isset($products_arr['ids'])) {
							$criteria=new CDbCriteria;
							//$criteria->order = 't.sort_category';
							//print_r($products_arr['ids']);
							$criteria->condition = " t.id IN (".implode(',', $products_arr['ids']).")";
							//$criteria->params=array(':root'=>Yii::app()->params['main_tree_root']);
							$models = Products::model()->findAll($criteria);//
							if (isset($models)) {
									$sum=0;
									$num_of_products=0;
									for($k=0; $k<count($models); $k++) {
										//echo $models[$k]->id;
										$sum=$sum+round($models[$k]->product_price*$products_arr['num'][$models[$k]->id], 2);
										$num_of_products = $num_of_products+$products_arr['num'][$models[$k]->id];
									}
									//echo $sum;
									return array($sum, $num_of_products);
							}/////if (isset($models)) {
					}//////if (isset($products_arr['ids'])) {
							
					}/////if (isset($cookie)) {/////////////////
					
					
					
				}/////////if (isset($cook)) {
		}//////public function CalculateCartContents(){
	
	
		public function filterEmptyFilter($filterChain){
			$filterChain->run();
		}
	
	
			public function getMessages($socket, $port, $dstip){ /////////////////Получение сообщений по выбранному порту
					
					//echo "port = ".$port."<br>";
					//echo "dstip = ".$dstip."<br>";
				
					$recieved = $socket->readMessages($port);//////////Получаем список сообщений
					$incoming = array();
					$criteria=new CDbCriteria;
					$criteria->condition = " 	t.type = :type AND t.read = :read  AND remoteport=:remoteport AND remoteip = :remoteip";
					$criteria->params = array(':type'=>0, ':read'=>1, ':remoteport'=>$port, 'remoteip'=>$dstip);

					$criteria->limit = 20;
					$criteria->order = "t.id DESC";
					$incoms = Socketworks::model()->findAll($criteria);
								
						if(isset($incoms)){
								for ($i=0; $i<count($incoms); $i++) {
									$cpr = new Cup200Protocol;
									if($port==3010) $cpr->readTMMessage($incoms[$i]->message);
									else $cpr->readMessage($incoms[$i]->message);/////////Остальное
									$cps[] = array('message'=>$cpr, 'datetime'=>$incoms[$i]->datetime, 'id'=>$incoms[$i]->id, 'source'=>$incoms[$i]->message);
								}
						}
					if (isset($cps)) return $cps;
					
			}
	
	
			public function  setDeviceParametrMSocket($device_type, $device_numer, $param_numer, $param_value, $command, $socket, $UnitMark=NULL) {
			
				//var_dump($param_value);
				
			
				if($UnitMark == NULL AND $device_type == 210) 	$UnitMark = 160;
				else $UnitMark = $this->getUnitMarkFromAccess($device_type, $device_numer);
				//$command = 53;
				
				$cpn = new ContProtocol();
				$time = microtime();
				$time_parts = explode(" ", $time);
				$cpn->hdr['T_com_for_kvit'][2] = mktime(22,20,0,9,8, 1974);
				$cpn->hdr['T_create_ticks'][2] =mktime(22,20,0,9,8, 1974);
				$cpn->hdr['CommandNum'][2] =$command;
				$cpn->hdr['UnitType'][2] =$device_type;
				$cpn->hdr['UnitMark'][2] =$UnitMark;
				$cpn->hdr['UnitNum'][2] =$device_numer;
				if(is_array($param_value)==true) {
					for($i=0; $i<count($param_value); $i++){
						if($param_value[$i]=="True" OR $param_value[$i]=="False"){
							if ($param_value[$i]=="True") $param_value_conv=1;
							else $param_value_conv=0;
							$cpn->pars[]=array('type'=>array('4', 'int', 5), 'value'=>array('8', 'int32', $param_value_conv));
						}
						else {
							//$param_value_conv = intval($param_value[$i]);
							if($device_type==60 OR  $device_type==63 OR  $device_type==61) {///////////////////Для конвертера идут все дабл
								//echo $param_value[$i];
								if($device_type==63 AND $command==63) $cpn->pars[]=array('type'=>array('4', 'int', 3), 'value'=>array('8', 'double', $param_value[$i]));
								 elseif($command==38) $cpn->pars[]=array('type'=>array('4', 'int', 3), 'value'=>array('8', 'double', $param_value[$i]));
								 else $cpn->pars[]=array('type'=>array('4', 'int', 2), 'value'=>array('8', 'double', $param_value[$i]));
							}
							else $cpn->pars[]=array('type'=>array('4', 'int', 3), 'value'=>array('8', 'double', $param_value[$i]));
						}
					} 
					
					//print_r($cpn->pars);
					//exit();
				}
				else $cpn->pars=array(
					0=>array('type'=>array('4', 'int', 3), 'value'=>array('8', 'double', $param_value)),
					//1=>array('type'=>array('4', 'int', $param_numer), 'value'=>array('8', 'double', $param_value))
				);
				
				//exit();
				//$cp->getHdrByte();
				$cpn->getParsByte();
				$cpn->finalizeMassage();
				
				//print_r($cpn->byteHeader);
				//echo 'SiteController $cpn->byteHeader: '.strlen($cpn->byteHeader);
				
				$socket->Write( $cpn->byteHeader , $cpn->hdr);
			
			
	}
	
	public function getUnitMarkFromAccess($device_type, $device_numer){
	
		$res=Yii::app()->db2->createCommand('select * FROM J300_ZS_EQUIPMENT WHERE  (nType = '.$device_type.' AND LocalNum = '.$device_numer.')');
		$row=$res->queryAll();
		if(isset($row) AND $row!=NULL)  return $row[0]['nMarka'];
		else {
			echo 'Не удалось определить марку устройства.  public function getUnitMarkFromAccess('.$device_type.','.$device_numer.')';
			exit();
		}
	}
	
	public function reverse($income)
	{
		$outcome = '';
		//echo strlen($income);
		/*
		for($i=0; $i<strlen($income); $i++){
			echo $i.': '.ord($income[$i]).'<br>';
		}
		*/
		if(strlen($income)==4) {
			$qqq = $income[3];
			$qqq.= $income[2];
			$qqq.= $income[1];
			$qqq.= $income[0];
		}
		if(strlen($income)==2) {
			$qqq= $income[1];
			$qqq.= $income[0];
		}
			return $qqq;
	}
	
	public function showBytes($pack, $name, $field_val){
	echo '<div><div style="float:left; width:150px">'.$name.'<br>'.$field_val.'</div><div style="float:left">';
	echo 'strlen = '.strlen($pack);
	for($i=0; $i<strlen($pack); $i++){
		//echo $i.': '.ord($pack[$i]).'<br>';
		echo $i.': '.$pack[$i].'<br>';
	}
	echo '</div>';
	echo '</div><br style="clear:both">';
}
	
		/////////////Читаем сообщения  из сокета и пишем в БД
	public function saveMessages($content, $dstip, $dstport){
			$prot_bin=$this->reverse(pack("I", '1234567890'));
			$smes = 0;
			$mes_start = 1;
			//var_dump($mes_start);
			$cps = array();
			
			while($mes_start!==false){
				$mes_start = strpos($content, $prot_bin, $smes);
			//	echo   '$mes_start= ';
			//	var_dump($mes_start);
				//echo '<br>';
				
				if($mes_start>=0 AND $mes_start!==false){
					$mes_len =unpack("I", $this->reverse(mb_substr($content, $mes_start+4, 4))) ;
					//$this->showBytes($this->reverse(mb_substr($content, $mes_start+4, 4)), 'mes_len',$mes_len[1]);
					
					//echo '1<br>';

					
					$cpr = new ContProtocol;
					$message = mb_substr($content,$mes_start, $mes_len[1]);
					
					//echo 'message = ';
					//var_dump($message);
					
					if($message!=''){
						$sw = new Socketworks();
						$sw->message = $message;
						$cpr = new Cup200Protocol;
						$cpr->readMessage($message);
						$time = microtime();
						$time_parts = explode(" ", $time);
						$sw->datetimeint = $time_parts[0];
						$sw->remoteip = $dstip;
						$sw->remoteport = $dstport;
						$sw->buf = serialize($cpr->buf);
						try {
									$sw->save();
									} catch (Exception $e) {
									 echo 'Ошибка сохранения в БД: ',  $e->getMessage(), "\n";
									 echo '<br>';
									 $this->showBytes($this->reverse(mb_substr($content, $mes_start+4, 4)), 'mes_len',$mes_len[1]);
									 var_dump($message);
									 echo '<br>';
									 exit();
									}//////////////////////
					}
					$smes = $mes_start+3;
					//$smes = $mes_start+$mes_len[1]-1;
				}
				
				
			}
		

	}//////////private function saveMessages(){
	
	protected function gameslist(){
		$game_models = App::model()->findAll();
		return CHtml::listdata($game_models, 'id', 'name');
	
	}
	
	protected function userList(){
		$models = Clients::model()->findAll();
		return CHtml::listdata($models, 'id', 'login');
	}
	
	protected function achievementList(){
		$models = Achievements::model()->findAll();
		return CHtml::listdata($models, 'id', 'name');
	}
	

	
	
}