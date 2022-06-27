<?php
///////////////////////////Это основное приложения связи Цуп 200 с Армом

//include('E:\web\wwwroot\arm\protected\models\Socketcommand.php');
$path = 'E:\web\wwwroot\sockserver\protected';

include_once($path.'\components\SocketServer.php');
include_once($path.'\components\FHtml.php');
include_once($path.'\components\Protocol.php');
include_once($path.'\components\Enums.php'); ///////////////	Перечисления
include_once($path.'\models\Tablesworks.php'); ///////////////	Обработка запросов по таблицам
include_once($path.'\models\Clients.php');
include_once($path.'\models\Leaderboards.php');
include_once($path.'\models\Scores.php');
include_once($path.'\models\Achievements.php');
include_once($path.'\models\Achievementdata.php');

class ServerCommand extends CConsoleCommand
{
	
	private $dstip;
	private $dstport;
	private $dstab;
	private $srcab;
	private $lvsnumsrc;
	private $lvsnumdst;
	
	private $server;
	private $threadHook;
	private $SockServIp =  '10.10.0.16';
	private $omTimeout = 1000; //////////Таймоут, через сколько закрывать консоль, если существует только одно соединение (процесс опроса процесов сокетов)
	private $lastTimeConnected; ///////////Число секунд (от 1.01.1970), когда последний раз было больше 1 соединения
	private $SockServPort ;

	
	public $ports = array(3031, 3033, 4000, 3010);////////////Список портов по каким устанавливать постоянное соединени
	public $forceclosedports=array();
	
	public $pdo_conn;
	

	
	//E:\web\wwwroot\arm\protected>yiic websocket index --command=websocketstart --site=arm  --lvsnumsrc=20 --lvsnumdst=21 --dstab=3
	public function actionIndex($c /*, $lvsnumsrc , $lvsnumdst, $dstab, $srcab, $conrollerport*/)	{ ////////////Соединение Арма с контроллером
		

		
		$command = $c;
		
		
		if($command=='start') {
			
			
			

			
			
			$this->SockServPort = 2610; //////////// для панели управления 
			$this->server = new SocketServer($this->SockServIp, $this->SockServPort, 86400);

			$this->server->hookControl = function($mes){
				//print_r($mes);
				if($mes[1]=='socketserver' AND $mes[2]=='socket') { ///////////
					//echo "mes = ".$mes[4];
					//var_dump(is_numeric($mes[3]));
					if(is_numeric($mes[3])==true AND trim($mes[4])!=''){
						if(isset($mes[4]) AND  $mes[4]=='close'){
							if(in_array($mes[3], $this->forceclosedports)==false)array_push($this->forceclosedports, $mes[3]);
						}
						if(isset($mes[4]) AND  $mes[4]=='open'){
							if(in_array($mes[3], $this->forceclosedports)==true) {
								$key  = array_search($mes[3], $this->forceclosedports);
								//echo "key = $key\n\r";
								if(isset($key) AND $key !==NULL) unset($this->forceclosedports[$key]);
								$params[0]=$mes[3];//port
								$params[1]=$mes[5];//ip
								$this->server->loopsockets[$mes[3]] = new SocketLoopThread($params, $this->SockServIp, $this->SockServPort);
								$this->server->loopsockets[$mes[3]]->start();
								
							}
						}
					}
					
				}
				//print_r($this->forceclosedports);
				
			};
			
			
			
			$this->server->hookTableWorks = function($cpr){///////////////////Передаем эту функцию в компонент SocketServer для обработки таблиц запросов
				$data = Tablesworks::proccedCommand($cpr, $this);
				if(empty($data)==false) $this->respondAsker($cpr, $data);
				else echo "\n\rServerCommand: nothing to send";
			};
			
			$this->server->gameCenterWorks = function(Protocol $cpr){///////////////////Передаем эту функцию в компонент SocketServer для обработки таблиц запросов
				//////////Пишем лог для броузера
				$mes = "From ".FHtml::InttoIP($cpr->hdr['Int32 _ip'][2]).", request: ".ClientMessageTypes::getByVal($cpr->hdr['Int32 _mestype'][2]);
				$this->server->notifyWebClients("updateprotokol##".str_replace(' ', '++', $mes));
				
				switch ($cpr->hdr['Int32 _request'][2]){
					case ClientMessageTypes::reportScore: $data = Scores::reportScore($cpr);
					default: $data = Tablesworks::proccedCommand($cpr, $this);
				}
				if(empty($data)==false) $this->respondAsker($cpr, $data, 1);
				else {
					//echo "\n\rServerCommand: nothing to send";
					$this->respondAsker($cpr, null, 0);
				}
			};
			
			$this->server->gameCenterUser = function(Protocol $cpr){ //////////Процедуры аутентификации
				//$data 
				
				
				//////////Пишем лог для броузера
				$mes = "From ".FHtml::InttoIP($cpr->hdr['Int32 _ip'][2]).", request: ".ClientMessageTypes::getByVal($cpr->hdr['Int32 _mestype'][2]);
				$this->server->notifyWebClients("updateprotokol##".str_replace(' ', '++', $mes));
				
				switch ($cpr->hdr['Int32 _request'][2]){
					case ClientMessageTypes::authenticateLocalPlayer:////
						preg_match_all ('|<xml>(.*)</xml>|isU', $cpr->utf8buf, $content2, PREG_SET_ORDER);
						$contents = $content2['0']['1'];
						try {
							$xml = new SimpleXMLElement($contents);
						} catch (Exception $e) {
							echo 'Caught exception:  Ссылка: '.$rabota_links[$s].' ',  $e->getMessage(), "\n";
						}///////////////////
						if (is_object($xml)) {
							$data = Clients::autentificate($cpr->hdr['Int32 _client'][2], $xml->password);
							$this->respondAsker($cpr, $data, 1);
							//print_r($data);
						}
						else {
							////////////
						}
					break; 
				}
				
			
			};
			
		

		$this->server->hookSocketcontrol = function(){
			
			
			
			$this->checkforTimeout(count($this->server->connections));
			
			//////////////Шлем в клиентские соединения вебсокета статус
			$armsockets = array(); 
			for($i=0; $i<count($this->ports);$i++) $armsockets[$this->ports[$i]]='0';
			
			
			if(empty($this->server->loopsockets)==false AND is_array($this->server->loopsockets)){
				//echo "sockets count = ".count($this->server->loopsockets)."\n\r";
				foreach ($this->server->loopsockets as $port=>$threadsocket){
					if($this->server->loopsockets[$port]!=NULL AND $this->server->loopsockets[$port]->isRunning()) {
						
						
						if(in_array($port, $this->forceclosedports)==true){
							$this->server->loopsockets[$port]->killMe();
							$this->server->loopsockets[$port]=NULL;
							unset($this->server->loopsockets[$port]);
							return;
						}
						
						
						 $this->server->loopsockets[$port]->SendFromDb($this->pdo_conn);
						 if($this->server->loopsockets[$port]->getConnectionStatus()=='connected') $armsockets[$port]='1';
						 else $armsockets[$port]='2';
					}
					else unset($this->server->loopsockets[$port]);
				}
				
				//print_r($armsockets);
				
			}
			
			
			$this->server->notifyClients('connectionstatus##'.json_encode(array('armsockets'=>$armsockets)));

		};
		
		
		
		//////////////////В одельном потоке запускаем обходчик всех соединений.  Он будет коннектится по сокету в webсокетсервер, и там запускать hookSocketcontrol
		$this->lastTimeConnected = time();
		$dbciclethread = new LoopThread($this->SockServIp, $this->SockServPort);
		$dbciclethread->start();
		
		$this->server->Start();////////дальше этого не идёт
		
		
		
		}
		
				
	}
	
	
	/** Посылает сообщение клиенту, определеннома в кадре $cpr через метод sendToClient
	 * @param Protocol $cpr кадр  протокола запроса
	 * @param string $xml данные для текстовой части ответа
	 */
	private function respondAsker($cpr, $xml, $succsess){
		$frame = new Protocol();
		$frame->hdr['Int32 _respond'][2] = $cpr->hdr['Int32 _request'][2];
		$frame->hdr['Int32 _ip'][2] = FHtml::IPtoInt($this->SockServIp);
		$frame->hdr['Int32 _port'][2] = $this->SockServPort;
		$frame->hdr['Int32 _rezult'][2] = $succsess;
		$frame->SendBell($xml,  0, 'CP1251');
		
		///////////////////////////Отсылаем клиенту
		$ip = FHtml::InttoIP($cpr->hdr['Int32 _ip'][2]); ////////////Конвертим интовый IP в string
		$this->server->sendToClient($frame->byteHeader, $ip, $cpr->hdr['Int32 _port'][2], $cpr->hdr['Int32 _client'][2]);
	}
	
	private function checkforTimeout($numofconnections){
		
		if($numofconnections>1) $this->lastTimeConnected = time();
		else {
			$currentTime = time();
			echo "OM shutdown in ".($this->omTimeout-($currentTime-$this->lastTimeConnected))."\n\r";
			if($currentTime-$this->lastTimeConnected >= $this->omTimeout) $this->server->socketServerShutdown();
		}
		return;
	}
	

}

class LoopThread extends Thread { ////////////Процес для зацикливания какогото действия
	
	private $webSockServIp;
	private $webSockServPort;
	private $stop;
	private $conn;
	
	
	public function __construct($webSockServIp, $webSockServPort){
	
		$this->webSockServIp = $webSockServIp;
		$this->webSockServPort = $webSockServPort;
	
	}
	
	public function run() {
		$this->cicle ();//////////////////Начинаем опрос сокета
	}
	
	
	public function cicle(){
		for($i=0; 1<2; $i++){
			if($this->stop==true) break;
			//echo "m:".$i;
			$this->requestSocketTreads();//////////////Опрашиваем через вэбсокетсервер сигнал чтобы активные сокеты считали из БД и отослали данные
			
			//time_nanosleep(0, 125000000);
			sleep(1);
			
		}
		
		
		
	}
	
	public function killMe(){
		$this->stop=true;
		//echo "....kiiiiilllllllll......\n\r";
	
	}
	
	private function requestSocketTreads(){
		if($this->conn!=null AND empty($this->conn)==false) $pop_conn = $this->conn;
		else {
			$pop_conn = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp')); //открываем сокет
			$connected = @socket_connect ( $pop_conn  , $this->webSockServIp,  $this->webSockServPort  );
			if($connected==false) { //////////////////Не смог соединиться с сокет сервером
				socket_set_nonblock($pop_conn);
				set_time_limit(86400);
				@fclose($pop_conn);
				echo "\n\rTime process was closed !!!. Buy.\n\r";
				exit();
			}
		}
		//print_r($pop_conn);
		if(isset( $pop_conn) and  $pop_conn!=NULL ) {
			
			//echo " loopcicle\n\r";
			//print_r($pop_conn);
			
			$write = @socket_write($pop_conn,  'extra:socketrequest:');
			if($write!=false) {
				$this->conn = $pop_conn;
			}
			else {
					socket_close($pop_conn);
					unset($pop_conn);
					socket_close($this->conn);
					unset($this->conn);
			}
		
		}

	}
	
	//////////YНе удалять, должна быть пустая
	public function SendFromBdtoArm(){//
	}

}


