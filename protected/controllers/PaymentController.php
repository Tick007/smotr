<?php

class PaymentController extends Controller/////////////Контроллер Эмулятора платежной системы на основе для онлайн платежей робокассы
{
	var $OutSum;
	var $InvId;
	var $SignatureValue;
	var $merchant; ////////////////// Магазин, мерчант
	var $payment; //// запись в таблице характеризующая оплату по заказу
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations  
			'CheckNumberofContragents +addfirm',//////////////Смотрим, сколько уже на пользователя заведено фирм
			'CheckRkassaresultvalues + rkassaresult',//////// Проверка, открывает ли пользователь своё сообщение или нет 
			'CheckMerchant + pay', /////////////////Проверка на существование мерчанта
		    'CheckOrderStatus + pay', ///////////////////Проверка не был ли уже оплачен заказ или нет
		    'CheckOrderSum + pay',
		    
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
		array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('index', 'rkassaresult', 'pay'),
				'users'=>array('*'), 
		),
		array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('rkassasuccess', 'rkassafai'),
				'users'=>array('@'),
		),
		array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
		),
		array('deny',  // deny all users
				'users'=>array('*'),
		),
		);
	}

	public function filterCheckOrderSum($filterChain){
	    $sum = Yii::app()->getRequest()->getParam('summ', NULL);
	    if($sum!=NULL){
	        if(is_numeric($sum)==false || $sum==0){
	            throw new CHttpException(500,'Неправильная сумма по заказу');
	            exit();
	        }
	    }
	    else {
	        throw new CHttpException(500,'Номер заказа не передан');
	        exit();
	    }
	    //TODO
	    $filterChain->run();
	}
	

	/** Метод проверки статуса заказа. Смотрим был оплачен или нет. Если существует и был оплачен, значит отбиваем
	 * @param unknown $filterChain
	 */
	public function filterCheckOrderStatus($filterChain){
	    $order_id = Yii::app()->getRequest()->getParam('order_id', NULL);
	    if($order_id!=NULL){
	        if(is_numeric($order_id)){
	            $payment = Payment::model()->findByAttributes(array('order_id'=>$order_id, 'shop_id'=>$this->merchant->id));
	            if($payment!=NULL){
	                if($payment->status==1){
	                    throw new CHttpException(500,'Заказ уже был оплачен');
	                    
	                    ////////////////Тут нужно бы ридерекнуть на сайт магазина с сообщением об ошибке
	                    
	                    exit();
	                }
	                elseif($payment->status==0){
	                    $this->payment = $payment;
	                }
	            }
	        }
	        else {
	            throw new CHttpException(500,'Неправильный формат номера заказа');
	            exit();
	        }
	    }
	    else {
	        throw new CHttpException(500,'Номер заказа не передан');
	        exit();
	    }
	    //TODO
	    $filterChain->run();
	}
	
	/** Метод проверки существует ли мерчант. Также вытаскиваем его для дальнейшей работы в свойство $merchant
	 * @param unknown $filterChain
	 * @throws CHttpException
	 */
	public function  filterCheckMerchant($filterChain){
	    $this->layout = 'empty';
	    $shop_id = Yii::app()->getRequest()->getParam('shop_id', NULL);
	    if($shop_id!=NULL && is_numeric($shop_id)){
	        $merchant = PaymentMerchants::model()->findByPk($shop_id);
	        if($merchant!=NULL){
	            if($merchant->status==0){
	                throw new CHttpException(500,'Магазин отключен от системы');
	                exit();
	            }
	            else $this->merchant = $merchant;
	        }
	        else {
	            throw new CHttpException(500,'Магазин не найден');
	            exit();
	        }
	    }
	    else{
	        throw new CHttpException(500,'Неверный формат идентификатора мерчанта');
	        exit();
	    }
	    
	    $filterChain->run();
	}

	public function filterCheckRkassaresultvalues ($filterChain) {
		////////Смотрим сколько фирм у пользователя
		$OutSum = Yii::app()->getRequest()->getParam('OutSum', NULL);
		$InvId = Yii::app()->getRequest()->getParam('InvId', NULL);
		$SignatureValue = Yii::app()->getRequest()->getParam('SignatureValue', NULL);
		if($SignatureValue != NULL)	 $SignatureValue = strtolower($SignatureValue);
			
		if ($OutSum != NULL AND $InvId != NULL AND $SignatureValue != NULL) {
			$this->OutSum = $OutSum;
			$this->InvId = $InvId;
			$this->SignatureValue = $SignatureValue;
			$filterChain->run();
		}//////////////if ($OutSum != NULL AND $InvId != NULL AND $SignatureValue != NULL) {
		else {
			throw new CHttpException(401,'Недостаточно параметров');
		}
	}////////////////////////////////////public function filt






	public function actionIndex(){
		/////////public function actionIndex(){
		$this->render('index');
	}//////////public function actionIndex(){


	public function actionRkassasuccess(){
		///////////Success URL (используется в случае успешного проведения платежа); Что нить пишем, и редиректи в ЛК /////Только для авторизованных
			
	}//////////public function actionRkassaok(){/

	public function actionRkassafail(){
		///////////Success URL (используется в случае успешного проведения платежа); ///////Что нить пишем и отправляем в лчный кабинет  /////Только для авторизованных
			
	}//////////public function actionRkassaok(){/


	public function actionRkassaresult(){
		/////////Сюда будет ломиться робот робокассы сообщая результат платежа
		//echo $this->InvId.'<br>';
		//echo $this->OutSum.'<br>';
		//echo $this->SignatureValue.'<br>';


		$order = Account_order::model()->findByAttributes(array('id'=>$this->InvId));
		if (isset($order->id)) {
			//////////////Создаем транзакцию в системе
			$RKASSA = new Robokassa; ////////////Смотрим параметры
			$mrh_pass2 = $RKASSA->mrh_pass2; //////Здесь идет проверка на 2й пароль
			////////Т.е. платили под одним паролем, результаты робокасса сообщает под другим
			///$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));
			if (md5($this->OutSum.':'.$this->InvId.':'.$mrh_pass2.':Shp_item='.$RKASSA->shp_item)==$this->SignatureValue) {

				////////Смотрим нет ли транзакций с таким номером заказа account_order_id
				$DEBET = Account_debet::model()->findByAttributes(array('schet'=>$order->id));
				if (isset($DEBET->id)==false) {
					//////Всё ок, транзакции с таким заказом нет
					if(isset($RKASSA->procent)) $this->OutSum = $this->OutSum*(1-$RKASSA->procent);
					
					/////////////////////Смотрим баланс пользователя до оплаты
					$old_balance = $this->getBalance($order->account_id);
					
					$NEWDEBET = new Account_debet;
					$NEWDEBET->account_id = $order->account_id;
					$NEWDEBET->operation_datetime = time();
					$NEWDEBET->money = $this->OutSum;
					$NEWDEBET->comments = 'Платёж через робокассу';
					$NEWDEBET->transaction_type = 2;
					$NEWDEBET->schet =  $order->id;
					//print_r($NEWDEBET->attributes);


					try {

						$NEWDEBET->save();
						echo 'OK'. $order->id;

					} catch (Exception $e) {
						echo 'Ошибка сохранения платежа: ',  $e->getMessage(), "\n";
					}

					if (isset($NEWDEBET->id)) {
						$order->debet_id = $NEWDEBET->id;
						$order->payment_system = 1;//////////////Ставим что это робокасса, потому что до этого человек мог кликнуть на форму счета - и сюда прописалось 5.
						try {
							$order->save();
							$new_balance = $this->getBalance($order->account_id);
							$this->sendnotification($order, $NEWDEBET, $old_balance, $new_balance );
						} catch (Exception $e) {
							echo 'Ошибка сохранения заказа: ',  $e->getMessage(), "\n";
						}/////////////////////
					}////////if (isset($NEWDEBET->id)) {


				}/////////////if (isset($DEBET->id)==false) {//////Всё ок, транзакции с таким заказом нет
			}/////////////if (md5()==$this->SignatureValue) {
			else 	{
				print "Content-type: text/html\n\nbad sign\n";
				die("incorrect sign passed");
			}/////////else 	{
		}/////////////if (isset($order->id)) {//////////////С
		else echo 'fail';

	}//////////public function actionRkassaok(){/

	public function sendnotification($order, $NEWDEBET, $old_balance, $new_balance){////////////отправка  сообщения в случае успеха
			$msg_body = 	"Поступила оплата через Робокассу на сумму ".$this->OutSum." по внутреннему счету N ".$order->id.". Дата оплаты: ".date('d.M.Y', $NEWDEBET->operation_datetime).", внутренний номер прихода: ".$NEWDEBET->id.". Баланс до зачисления: $old_balance. Баланс после зачисления: $new_balance";
			
			$msg_body = iconv( "UTF-8", "CP1251", $msg_body);
				
			$headers = 'From: '.Yii::app()->params['supportEmail1']. "\r\n" ;
			$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";

			@mail(Yii::app()->params['supportEmail'],  iconv( "UTF-8", "CP1251", 'Поступление оплаты ').$_SERVER['HTTP_HOST'], $msg_body, $headers)	;//Yii::app()->user->setFlash('contact','Заявка создана. Мы свяжемся с вами в ближайшее время.');
			
			if (isset($order->user_id )) {////////////
				$client = Clients::model()->findByPk($order->user_id);
				if(isset($client->client_email)) {
						@mail($client->client_email,  iconv( "UTF-8", "CP1251", 'Поступление оплаты').$_SERVER['HTTP_HOST'], $msg_body, $headers)	;//Yii::app()->user->setFlash('contact','Заявка создана. Мы свяжемся с вами в ближайшее время.');
				}////////////if(isset($client->client_email)) {
			}
			
	}/////////////public function sendnotification

	public function actionPayrobokassa() {
		////////////Редирект на шлюз робокассы

	}/////////////////public function action Payrobokassa() { ////////////

	private function getBalance($contr_agent_id){
		/////////////////////Получение баланса пользователя по его организации



				$criteria=new CDbCriteria;
				$criteria->condition = " t.account_id = :contr_agent ";
				$params = array(':contr_agent'=>$contr_agent_id );
				$criteria->params = $params;
				$criteria->order= ' t.operation_datetime DESC ';
				$KREDIT = Account_kredit::model()->findAll($criteria);
				$summa_kredit = 0;
				for ($i=0; $i<count($KREDIT); $i++) $summa_kredit = $summa_kredit+$KREDIT[$i]->money;
					
				//echo $summa_kredit ;
					
				/////////////////////Теперь выбираем все приходные транзакции
				$summa_debet = 0;
				$criteria=new CDbCriteria;
				$criteria->condition = " t.account_id = :cat_id";
				$criteria->order = 't.id';
				$criteria->params=array(':cat_id'=>$contr_agent_id );
				$DEBET = Account_debet::model()->findAll($criteria);
				$summa_debet = 0;
				for ($i=0; $i<count($DEBET); $i++) $summa_debet = $summa_debet+$DEBET[$i]->money;

		if (isset($summa_kredit) OR isset($summa_debet) ) return (int)$summa_debet - (int)$summa_kredit;
		else return 0;
			
	}//////////////////////private function getBalance(){//////////////////

	
	/**Метод на который будет редиректится сайт когда нужно оплатить заказ
	 * Это эмулятор интерфейса платежной системы для ввода реквизитов карты
	 * Нужен для отработки взаимодействия с основной машиной на UBUNTU
	 * 
	 */
	public function actionPay() {
	    
	    $this->layout = 'empty';
	    
	    $model=new Payment;
	    
	    ////////////////Всегда эти берем из гета
	    $order_id = Yii::app()->getRequest()->getParam('order_id'); /////////на NULL в фильтре проверили
	    $sum = Yii::app()->getRequest()->getParam('summ'); /////////на NULL в фильтре проверил
	    $signature = Yii::app()->getRequest()->getParam('signaturevalue', NULL);
	    
	    if(isset($_POST['return_url'])) $return_url = trim($_POST['return_url']);
	    else{
	       $request = Yii::app()->getRequest();
	       $return_url = $request->getUrlReferrer();
	    }
	    //print_r($return_url);
	  //  echo '<br>';

	    
	    
	    // if it is ajax validation request
	    /*
	    if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
	    {
	        //echo '1';
	        //exit();
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    */
	    
	    // collect user input data
	    if(isset($_POST['Payment']))
	    {
	        
	        ////////////Вот тут нужно смотреть, есть ли уже такой заказ в БД и не оплачен ли он уже
	        if(isset($this->payment) && is_null($this->payment)==false) $model = $this->payment;
	        
	        $model->attributes=$_POST['Payment'];
	       
	 	       if($model->validate()){
	           
	           /////////Проводим операцию
	            
	           //////Проверяем подпись по заказ
	           $checkSignature = $this->checkMerchantAuth($order_id, $sum, $signature);
	           
	           if($checkSignature == true) {
	              
	               /////////////////Проведение операции
	               ////Уведомления сайта мерчанта о статусе операции (платежа) в фоне с помошью CURL
	               
	               ////////////////редирект на статус заказа
	               
	               $processed = $this->processPayment($order_id, $sum, $signature, $model);
	               if($processed==TRUE){
	                   if(trim($return_url)!='') $this->redirect($return_url);
	                   else $this->redirect($this->merchant->shopurl);
	               }
	               else{
	                   throw new CHttpException(500,'Проведение платежа не удалось');
	               }
	               exit();
	               
	           }
	           else {
	               throw new CHttpException(500,'Ошибка при проверке подписи. Проверьте настройки авторизации для магазина');

	               exit();
	           }
	           
	          
	           //exit();
	          
	       }
	       
	       
	       
	    }
	    else{ /////////////Первый прилет из магазина
	        
	       
	    }
	    
	    
	    /////TODO/////////Вообюще тут сруузу нужно проверять на соответствие параметры подписи и других параметров. Также
	    ////// нужно сделать защиту что заказ уже оплачен
	    
	    $model->shop_id = $this->merchant->id;
	    
	    if($signature!=NULL){
	       
	        $model->signature = $signature;
	        $model->sum = $sum;
	        $model->order_id = $order_id;
	        
	        //////Проверяем подпись по заказ
	        $checkSignature = $this->checkMerchantAuth($order_id, $sum, $signature);
	        
	        if($checkSignature == true) $this->render('paymentform', array('model'=>$model, 'merchant'=>$this->merchant, 'return_url'=>$return_url));
	        else{
	            throw new CHttpException(500,'Ошибка при проверке подписи. Проверьте настройки авторизации для магазина');
	            exit();
	        }
	    }
	    else {
	        throw new CHttpException(500,'Нет подписи');
	        exit();
	    }
	}
	
	/** Основная функции проверки оплачиваемого заказа. Формируем подпись для заказа исходя из записей для магазина В БД и сравниваем с 
	 * полученной подписью
	 * @param unknown $order_id
	 * @param unknown $sum
	 * @param unknown $signature
	 */
	protected function checkMerchantAuth($order_id, $sum, $signature){
	    
	    $crc_system_params = $this->calculate_crc($order_id, $sum, $this->merchant->payment_pass, $this->merchant->login);
	    //echo $crc_system_params;
	    if($crc_system_params == $signature) $checkResult = true;
	    else $checkResult = FALSE;
	    
	    return($checkResult);
	}
	
	/**Подсчет crc при приеме запроса на платёж
	 * @param unknown $order_id
	 * @param unknown $sum
	 * @param unknown $pass
	 * @param unknown $login
	 * @return string
	 */
	private function calculate_crc($order_id, $sum, $pass, $login){
	    $crc = null;
	    $crc_string = $login.':';
	    $crc_string.= $sum.':';
	    $crc_string.= $order_id.':';
	    $crc_string.= $pass.':1';
	    $crc = md5($crc_string);
	    return $crc;
	}
	
	/** Подсчет crc для запроса на резалт урл магазина(мерчанта)
	 * @param unknown $order_id
	 * @param unknown $sum
	 * @param unknown $pass
	 * @param unknown $login
	 * @return string
	 */
	private function calculate_result_crc($order_id, $sum, $pass, $login){
	    $crc = null;
	    //$crc_string = '';
	    $crc_string = $sum.':';
	    $crc_string.= $order_id.':';
	    $crc_string.= $pass.':Shp_item=1';
	    //echo $crc_string.'<br>';
	    $crc = md5($crc_string);
	    return $crc;
	}
	
	
	protected function performAjaxValidation($model)
	{
	    if(isset($_POST['ajax']) && $_POST['ajax']==='payment-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	}
	
	
	/** Метод проведения операции в ПС. Также нужно уведометь сайт интернет магазина что операция оплаты прошла/не прошла
	 * @param int $order_id
	 * @param int $sum
	 * @param string $signature
	 * @param Payment $model
	 */
	private function processPayment(int $order_id, int $sum, string $signature, Payment $model){
	    
	    //2014-11-19 10:32:18
	    $operation_date = $this->udate('Y-m-d H:i:s.u');
	    $model->paid_date = $operation_date;
	    $model->status = 1;
	    try {
	        $model->save();
	        
	        $this->notifyMerchant('paid', $model );
	        
	        return TRUE;
	        
	    } catch (Exception $e) {
	        throw new CHttpException(500,$e->getMessage());
	        
	    }
        
	    return true;
	    
	}
	
	/**Метод уведомления магазина о статусе платежа
	 * Будем использовать curl
	 * 
	 * @param string $status
	 * @param Payment $payment
	 */
	private function notifyMerchant(string $status,  Payment $payment){
	    $merchant = $payment->merchant;
	    
	    $InvId = $payment->order_id;
	    $OutSum = $payment->sum;
	    $pass = $payment->merchant->result_pass;
	    $login = $payment->merchant->login;
	    
	    $crc = $this->calculate_result_crc($InvId,$OutSum,$pass,$login);
	    $url = $merchant->shopurl.'/'.$merchant->action_result;
	    $url.='?OutSum='.$OutSum.'&InvId='.$InvId.'&SignatureValue='.$crc;
	    $referer = $_SERVER['SERVER_NAME'];

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_REFERER, $referer);
	    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    
	    $output = curl_exec($ch); // get content
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // �������� HTTP-���
	    //echo '$output = '.$output;
	    // close curl resource to free up system resources
	    curl_close($ch);
	    
	    
	    
	}
	
	private function udate($format = 'u', $utimestamp = null) {
	    if (is_null($utimestamp))
	        $utimestamp = microtime(true);
	        
	        $timestamp = floor($utimestamp);
	        $milliseconds = round(($utimestamp - $timestamp) * 1000000);
	        
	        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
	}
	
}//////////////////class
