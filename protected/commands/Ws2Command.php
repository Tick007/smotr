<?php

//https://sergeyzhuk.me/2017/06/22/reactphp-chat-server/
require dirname(__FILE__) . '/vendor/autoload.php';

use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Socket;
// include 'app//socket.php';
use MyApp\Antenna;
use MyApp\DNConverter;
use MyApp\UPConverter;
use MyApp\K4c75_amplifier;
use MyApp\K4c75_redundancy_control;
use MyApp\LN134042030_mshu;
use MyApp\mitec163995_ttranslator;
use MyApp\keydown_3204_matrix;
use MyApp\ConvRedundancyControl;
use React\socket\TcpConnector;
use React\socket\TimeoutConnector;
use React\Promise;
use React\EventLoop\Factory;
use MyApp\Cortex;




class WS2Command extends CConsoleCommand
{

    var $server;

    var $clients;

    var $hundred_arr;

    public $pdo_conn;

    public $antenna;
    public $dnconv;
    public $upconv;
    public $amplifier;
    public $ttranslator;
    public $mshu;
    public $matrix;
    public $convredunt;
    public $cortex;

    public $devicelist;
    
    public $controller_loop;
    public $controller_period = 3;
    public $front_end_period = 1;
    public $control_client; ////////////Управляющее соединение по сокету
    public $monitoring_state = "Off";
    
    //private $cyclogramShowData = null;
    private $cyclogramData = array('list'=>array('a', 'b', 'c'));
    private $commandData = array();
    ///////////////////////////////////////////////////true очищает список исполнения со статусами
    
    private $cyclogram_execution_list=null; ///////////Список комманд циклограмм на исполнение
    
    private $db_table_list;
    
    public $need_send_conf=FALSE; ///////////////Признак отправлять конфигурацию или нет

    public function __construct()
    {
        //include ('d:\YandexDisk\wwroot\smotr\protected\components\ContProtocol.php'); // /////////Протокол контроллера
        include (dirname(__FILE__).'/../components/ContProtocol.php'); // /////////Протокол контроллера
        

        try {
            $hostname = "localhost";
            $dbname = "smotr_bd";
            $username = "root";
            $pw = "1234";
            $this->pdo_conn = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
        } catch (PDOException $e) {
            echo "Failed to get DB handle 1: " . $e->getMessage() . "\n";
            exit();
        }

        $this->antenna[1] = new Antenna($this->pdo_conn, 1);
        $this->antenna[2] = new Antenna($this->pdo_conn, 2);

        $this->dnconv[20] = new DNConverter($this->pdo_conn, 20);
        $this->dnconv[21] = new DNConverter($this->pdo_conn, 21);

        $this->upconv[10] = new UPConverter($this->pdo_conn, 10);
        $this->upconv[11] = new UPConverter($this->pdo_conn, 11);
        
        
        
        $this->amplifier[90] = new K4c75_amplifier($this->pdo_conn, 90);
        $this->amplifier[90]->redundancyUnit = new K4c75_redundancy_control();
        
        $this->amplifier[91] = new K4c75_amplifier($this->pdo_conn, 91);
        $this->amplifier[91]->redundancyUnit = $this->amplifier[90]->redundancyUnit;
        
        $this->ttranslator=new mitec163995_ttranslator($this->pdo_conn, 60);
        
        $this->mshu = new LN134042030_mshu($this->pdo_conn, 40);
        
        $this->matrix[30] = new keydown_3204_matrix($this->pdo_conn, 30);
        $this->matrix[31] = new keydown_3204_matrix($this->pdo_conn, 31);
        
        $this->convredunt[1]=new ConvRedundancyControl($this->pdo_conn, 1);
        $this->convredunt[2]=new ConvRedundancyControl($this->pdo_conn, 2);
      
        $this->cortex[1] = new Cortex($this->pdo_conn, 1);
        $this->cortex[2] = new Cortex($this->pdo_conn, 2);
    }

    public function actionIndex($c /*, $lvsnumsrc , $lvsnumdst, $dstab, $srcab, $conrollerport*/)	{ // //////////Соединение Арма с контроллером
        $command = $c;

        if ($command == 'start') {
            echo "startFunc\n\r";

            $hook = function () {
                $this->onReceivecallback();
            };
            
            //$this->armConfig(1);

            $this->initializeData();

            $server = IoServer::factory(new HttpServer(new WsServer(new Socket($this))), 8081);

            $server->loop->addPeriodicTimer($this->front_end_period, function () use ($server) {
                
                $this->sendLiveTag();
                $this->getAllParams($this->pdo_conn);
                $this->send100(); // /////Отправка в веб сокет
                                  // $this->queryHundred();//опрос контроллер
                //$this->sendLiveTag();
            });


            /*
             $period = $this->controller_period;
             $this->controller_loop = $server->loop->addPeriodicTimer($period, function () use ($server, $period) {
                $this->queryHundred(); // опрос контроллер
                $this->monitoring_state = "On";
            });
             */

            $this->server = $server;
            $this->server->run();

            echo 'afterrun'; // ////сюда уже не доходит
        }
    }

    /**
     * Загрузка начальных данных для работы
     */
    public function initializeData()
    {
        $this->queryArmController(); // /////////////////Один раз запросили список оборудования с конроллера
        echo 'reading device names from DB...........' . "\r\n";
        $sql = "SELECT `id`, `LocalNum`, `NameEq`, `nType`, `nMarka`,  `Description` FROM `j400_equipment`";
        if ($this->pdo_conn instanceof PDO) {
            try {
                $rez = $this->pdo_conn->query($sql);
            } catch (PDOException $e) {
                print_r($e);
            }
            if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
                foreach ($rez as $row) {
                    $this->devicelist[$row['LocalNum']] = array(
                        'id' => $row['id'],
                        'NameEq' => $row['NameEq'],
                        'nType'=>  $row['nType'],
                        'nMarka'=> $row['nMarka'],
                        'Description' => $row['Description']
                    );
                }
                // print_r($this->devicelist);
            }
        }
        
       
        
        ///////////Смотрим данные комманд, что бы не городить многовложенные запросы
        $sql="SELECT * FROM  j400_command WHERE P_TYPEEQUIP_ID <> 120"; ///////////исключили кортекс
        if ($this->pdo_conn instanceof PDO) {
            try {
                $rez = $this->pdo_conn->query($sql);
            } catch (PDOException $e) {
                print_r($e);
            }
            if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
                foreach ($rez as $row) {
                    $this->commandData[$row['P_MARKA_ID']][$row['P_TYPEEQUIP_ID']][$row['NUMCMD']]=array('NUMCMD'=>$row['NUMCMD'], 
                        'NAMECMD'=>$row['NAMECMD'], 'NAME_DEVICE'=>$row['NAME_DEVICE']);
                
            }
            //print_r($this->commandData);
            //echo "\n\r";
            }
        }
        
        
        $this->readCyclogramsFromDB();
        
    }

    ///////////////Тут только если что-то прилетело в $msg. Онконнект тут нет
    public function onReceivecallback(ConnectionInterface $from, $msg, \SplObjectStorage $clients)
    {
       // echo 'onReceive: ' . $msg . "\r\n";
       
        //print_r($from);
        
        $client =  $this->clients->current();
        $this->parseMessage($msg,$client);
        $this->clients = $clients;
    }

    /**
     * Метод для вытаскивания параметров из БД (на типа от контроллера или от конкретного устройства)
     */
    public function getAllParams($pdo_conn)
    {
        // echo "getAllParams\n\r";
        $sql = "SELECT `id`, `title`, `azimut`, `angle`, `scanner`, `workmode`, `signal_lvl` FROM `smotr_antenna`";

        if ($this->pdo_conn instanceof PDO) {

            try {
                $rez = $pdo_conn->query($sql);
            } catch (PDOException $e) {
                print_r($e);
            }

            // var_dump($rez);
            if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
                foreach ($rez as $row) {

                    try {
                        // print_r($row);
                        $this->antenna[$row['id']]->id = $row['id'];
                        $this->antenna[$row['id']]->title = $row['title'];
                        $this->antenna[$row['id']]->azimut = $row['azimut'];
                        $this->antenna[$row['id']]->angle = $row['angle'];
                        $this->antenna[$row['id']]->scanner = $row['scanner'];
                        $this->antenna[$row['id']]->workmode = $row['workmode'];
                        $this->antenna[$row['id']]->signal_lvl = $row['signal_lvl'];
                        // }
                    } catch (PDOException $e) {
                        echo 'Ошибка извлечения из БД: ', $e->getMessage(), "\n";
                        exit();
                    } // ////////////////////
                }
            } else
                echo 'no data in DB for antenna table' . "\r\n";
        } else {
            echo 'broken PDO object in getAllParams' . "\n\r";
        }
    }

    /**
     * отправка метки жизни клиентам
     */
    public function sendLiveTag()
    {
        echo 'livetag ' . date('H:i:s') . "\r\n";
        //var_dump($this->need_send_conf);
        //echo "\n\r";
        if (isset($this->clients)) {
            
            if ($this->need_send_conf==TRUE){
                
                $ArmConfig=$this->armConfig();/////////////Пока отдаем так
                $this->need_send_conf=FALSE;
            }
            
            foreach ($this->clients as $client) {
                $livetag = array(
                    'livetag' => date("d.m.Y H:i:s")
                );
                if(isset($ArmConfig) && $ArmConfig!=null) $livetag['configuration']=$ArmConfig;
               
                if($this->control_client!=null && $this->control_client==$client){
                    ////////do nothing
                }
                else {
                    //echo 'sending livetag and conf to ws....'."\n\r";
                    $client->send(json_encode($livetag));
                }
            }
        }
    }

    public function send100()
    {
        if (isset($this->clients)) {

            echo 'sending to ws....'."\n\r";

            // $data=array('device_params'=>array('param1'=>rand(50,100), 'param2'=>rand(11,30),'param3'=>rand(31,49)));
            
            if($this->monitoring_state=='On') $data = array(
                'state'=>$this->monitoring_state,
                'cortex'=>array(
                    '1' => $this->cortex[1]->getJsonData(),
                    '2' => $this->cortex[2]->getJsonData(),
                ),
                'DeviceParameters' => array(
                    
                    // 'antennaDeviceData' => $this->getAntennaData(),
                    'AntennaSystem' => $this->getAntennaDataById(1),
                    
                    'testTranslyatorDeviceData' => $this->ttranslator->getJsonData(),
                    'amplifierDeviceData' => array(
                        '1' => $this->amplifier[90]->getJsonData(),
                        '2' => $this->amplifier[91]->getJsonData(),
                        'redundancy'=>$this->amplifier[90]->redundancyUnit->getJsonData(),
                    ),
                    'MSHUDeviceData' => $this->getMSHUDeviceData(), //getMSHUDeviceData(),
                    // 'MSHUDeviceData2' => $this->getMSHUDeviceData(2),
                    'upConverterDeviceData' => array(
                        '1' => $this->upconv[10]->getJsonData(),
                        '2' => $this->upconv[11]->getJsonData()
                    ),
                    'downConverterDeviceData' => array(
                        '1' => $this->dnconv[20]->getJsonData(),
                        '2' => $this->dnconv[21]->getJsonData()
                    ),
                    'matrixDeviceData' => array(
                        '1' => $this->matrix[30]->getJsonData(),
                        '2' => $this->matrix[31]->getJsonData()
                    ),
                    'convRedundancyData'=>array(
                        '1' => $this->convredunt[1]->getJsonData(),
                        '2' => $this->convredunt[2]->getJsonData()
                    ),
                )
            );
            else $data = array('state'=>$this->monitoring_state, 'DeviceParameters' => '');
            foreach ($this->clients as $client) {
                    $client->send(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
            }
        }
    }
    
    

    

    /**
     * @param \SplObjectStorage $clients

     */
    public function onConnectionListChange(\SplObjectStorage $clients)
    {
        $this->clients = $clients;
        
        //echo 'conn'."\n\r";
        $this->need_send_conf = TRUE;
        //$this->sendConf($this->clients->current());
        //var_dump($this->need_send_conf);
    }

    private function parseMessage($param, $current_client)
    {
   /*  
        echo "parseMessage param = ";
        print_r($param);
        var_dump($param);
        echo "\n\r";
 */
        /*
        if($param==null || $param=''){///////////кто то подцепился без команды. Наверное хочет конфиг
            $this->sendConf($current_client);
        }*/
        
        if ($param != null && trim($param != '')) {
            $pieces = explode(':::', $param);
            if (is_array($pieces) == true) {
                if ($pieces[0] == "control")
                    $this->executeControlcommand($pieces[1]);
            }
        } 

        if($json_command = json_decode($param)){
            echo "command received: ";
            echo "\n\r";
            print_r($json_command);
            echo "\n\r";
            
                     
             if(isset($json_command->devicecontrol)){ ////////////////Управление устройствами когда нет контроллера Бега
                 if ($json_command->devicecontrol==30 && isset($json_command->devicedata) ){ //////////////обновляем матрицу
                     $this->matrix[30]->updateState($json_command->devicedata);
                 }
                 if ($json_command->devicecontrol==31 && isset($json_command->devicedata) ){ //////////////обновляем матрицу
                     $this->matrix[31]->updateState($json_command->devicedata);
                 }
             }
             
             if(isset($json_command->clientid)){
                 if($current_client!=null && empty($current_client)==false)try {
                     $current_client->clientid=$json_command->clientid;
                     
                 } catch (Exception $e) {
                 }
             }
             
            if(isset($json_command->cyclogram)){ ////
                $msg="ciclocommandprocess";
                if($json_command->cyclogram->dialog=='open'){ ///////Значит нужно сформировать в данных для интерфейса секцию с циклограммами
                    $msg="opening";
                    print_r("Sendeing cyclogramm list to WS");
                    echo "\n\r";
                    //$current_client->cyclogramShowData=true; ////это только для того кто прислал команду. Команда пришла из прокси web скрипта,
                    ///////////////////////////////////////////////т.е. из временного соединения
                    //$this->cyclogramShowData = true;
                    $this->readCyclogramsFromDB();
                    if(isset($json_command->cyclogram->clientid)) {
                        $data['cyclogram'] = $this->cyclogramData;
                        foreach ($this->clients as $client) {
                            if(isset($client->clientid) && $json_command->cyclogram->clientid==$client->clientid){
                                $client->send(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
                            }
                        }
                        
                    }
                }
                $current_client->send(json_encode($msg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
                
                
            }
            if(isset($json_command->command) && isset($json_command->command->clientid)){
                $msg="cyclogram command received";
                $current_client->send(json_encode($msg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
                $this->processCyclogramExecutionCommand($json_command->command, $json_command->command->clientid);
            }
            
            if(isset($json_command->state)){
                
                $this->control_client = $current_client;
                  
                if($json_command->state==true){ //////////////////Пробуем начать мониторинг сервера
                    
                    $this->queryArmController();
                    $this->queryHundred();
                    
                    $period = $this->controller_period;
                    $server = $this->server;
                    if($this->controller_loop==null) $this->controller_loop = $server->loop->addPeriodicTimer($period, function () use ($server, $period) {
                        $this->queryHundred(); // опрос контроллер
                    });
                    $this->monitoring_state = "On";
                    $current_client->send(json_encode("running", JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
                }
                elseif($json_command->state==false){
                    echo 'closing monitorind GS...'."\n\r";
                    if($this->controller_loop!=null) {
                        $this->server->loop->cancelTimer($this->controller_loop);
                        $this->monitoring_state = "Off";
                        if($current_client!=null){
                            @$current_client->send(json_encode("stoped", JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
                        }
                        $this->controller_loop=null;
                    }
                    
                }
            }
            
            
        }
        else {
            echo 'Empty message' . "\r\n";
        }
    }

    private function executeControlcommand($command)
    {
        if ($command == 'shutdown') {
            $this->server = null;
            exit();
        }
    }

    private function getAntennaData()
    {
        return array(
            'deviceParameters' => array(
                array(
                    'nameParameter' => 'signal',
                    'valueParameter' => rand(14, 21) . ' дБ'
                ),
                array(
                    'nameParameter' => 'azimut',
                    'valueParameter' => rand(45, 50) . ' град'
                ),
                array(
                    'nameParameter' => 'angle',
                    'valueParameter' => rand(15, 20) . ' град'
                ),
                array(
                    'nameParameter' => 'scanner',
                    'valueParameter' => (rand(0, 1) == 1 ? 'вкл' : 'выкл')
                ),
                array(
                    'nameParameter' => 'mode',
                    'valueParameter' => (rand(0, 1) == 1 ? 'Автомат' : 'По программе')
                )
            ),
            'title' => 'АНТЕННАЯ СИСТЕМА',
            'id' => 1
        );
    }

    // ///////////////////////////////////////Формирование данных приближенных к боевым
    private function getAntennaDataById($id)
    {
        if (isset($this->antenna) && $this->antenna != null && isset($this->antenna[$id]) == true) {
            return $this->antenna[$id]->getJsonData();
        } else
            return null;
    }





    /**
     * Метод для опроса контроллера женька командой 100.
     * Уже бинарники (то что летит в сокет) хранятся в $this->hundred_arr
     * По дибильному приходяится для каждой посылки устанавлиать соединения, т.к. буфер приема только в этом случае очищается
     */
    private function queryHundred()
    {
        $dstip = "10.10.0.162";
        $dstport = "1700";
        //echo "queryHundred\n\r";

        if ($this->hundred_arr != null) {
           
            echo "sending 100 command to controller..... \n\r";
            $arr = $this->hundred_arr;
            echo "Всего запросов: " . count($arr) . "\n\r";
            
            error_reporting(E_ERROR);
            $fp = fsockopen("tcp://".$dstip, $dstport, $errno, $errstr, 1);
            $time_start= microtime(true);
            $sended_once=false;
            if (!$fp) {
                echo "$errstr ($errno)<br />\n";
            } else {
                $i = 1;
                foreach ($arr as $num => $frame) {
                    if(fwrite($fp, $frame)){
                            time_nanosleep(0, 10000000);
                            $data=fread($fp,2048);
                            echo $i . ' request, получено ' . mb_strlen($data) . " байт \n\r";
                            $cpr = new ContProtocol();
                            $cpr->readMessage($data);
                            $cps[$cpr->hdr['UnitNum'][2]] = $cpr;
                    }
                    $i ++;
                    time_nanosleep(0, 10000000);
                    $time_iter= microtime(true);
                    if($time_iter-$time_start>1.5 && $sended_once==false) {
                        $this->sendLiveTag();
                        $this->send100();
                        $sended_once=true;
                    }
                }
                fclose($fp);
            }
            error_reporting(E_ALL);
            
            /* В этом варианте нет таймаута
            $pop_conn = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp')); // открываем сокет
            try {
                if (socket_connect($pop_conn, $dstip, $dstport)) {
                    echo "Всего запросов: " . count($arr) . "\n\r";
                    $i = 1;
                    foreach ($arr as $num => $frame) {
                        if(@socket_write($pop_conn, $frame)){
                        //time_nanosleep(0, 025000000);
                            $data = socket_read($pop_conn, 4048);
                            echo $i . ' request, получено ' . mb_strlen($data) . " байт \n\r";
                            $cpr = new ContProtocol();
                            $cpr->readMessage($data);
                            $cps[$cpr->hdr['UnitNum'][2]] = $cpr;
                        // print_r($cpr->hdr['UnitNum'][2]);
                        }
                        $i ++;
                    }
                } else
                    echo 'Could not send query to controller....' . "\n\r";
            } catch (Exception $e) {
                echo 'Could not send query to controller....' . "\n\r";
            }

            socket_close($pop_conn);*/

            /*
             *
             * echo "arr count = ".count($arr) ."\n\r";
             * $loop2 = React\EventLoop\Factory::create();
             * $connector2 = new React\Socket\Connector($loop2);
             * $cps=null;
             * echo '100 request'."\n\r";
             * $connector2->connect($dstip . ':' . $dstport)->then(
             * function (React\Socket\ConnectionInterface $conn) use ($loop2, &$arr, &$cps) {
             *
             * $i=1;
             * $conn->on('data', function ($data) use ($conn, $loop2, &$cps, &$i) {
             *
             * $cpr = new ContProtocol;
             * try {
             * $cpr->readMessage($data);
             * $cps[$cpr->hdr['UnitNum'][2]] = $cpr;
             * } catch (Exception $e) {
             * echo 'unsuccessiful protocol frame parcing '."\n\r";
             * }
             *
             * echo $i.": ".mb_strlen($data);
             * echo "\n\r";
             * $i++;
             * }, function (Exception $exception) use ($loop2) {
             * echo "Cannot data connect to server: " . $exception->getMessage();
             * $loop2->stop();
             * });
             *
             *
             * foreach ($arr as $num => $frame) {
             * $loop2->addTimer(0.3, function () use ($conn, $frame) {
             * $conn->write($frame);
             * });
             *
             * }
             *
             * $loop2->addTimer(3.2, function () use ($loop2) {
             * $loop2->stop();
             * });
             * });
             * $loop2->run();
             *
             */

            echo 'all answers received' . "\n\r";
            $time_end=microtime(true);
            echo "at ".($time_end- $time_start)."\n\r";
            // echo count($cps)."\n\r";
            if (isset($cps))
                foreach ($cps as $device_num => $lcp) {
                    if ($device_num > 0) {
                        if (isset($lcp->pars) and empty($lcp->pars) == false)
                            $params[$device_num] = $this->correct_paramtrs($lcp->pars);
                            //print_r($lcp->pars);
                           // echo "\n\r";
                    }
                }
            //print_r($params);
            echo "\n\r";
            if (isset($params) && isset($this->devicelist) && $this->devicelist != null && is_array($this->devicelist) == true) {
                foreach ($params as $device_num => $device_params) {
                    if (isset($this->devicelist[$device_num]))
                        $this->devicelist[$device_num]['current_values'] = $device_params;
                }
            }
             //print_r($this->devicelist);
            // ////////////Конвертора вниз
            if (isset($this->devicelist[20]) && isset($this->devicelist[20]['current_values']))
                $this->dnconv[20]->updateFromController($this->devicelist[20], 20);
            if (isset($this->devicelist[21]) && isset($this->devicelist[21]['current_values']))
                $this->dnconv[21]->updateFromController($this->devicelist[21], 21);

            // ////////////Конвертора вверх
            if (isset($this->devicelist[10]) && isset($this->devicelist[10]['current_values']))
                $this->upconv[10]->updateFromController($this->devicelist[10], 10);
            if (isset($this->devicelist[11]) && isset($this->devicelist[11]['current_values']))
                $this->upconv[11]->updateFromController($this->devicelist[11], 11);
            
                /////////Усилители
            if (isset($this->devicelist[90]) && isset($this->devicelist[90]['current_values']))
                $this->amplifier[90]->updateFromController($this->devicelist[90], 90);
            if (isset($this->devicelist[91]) && isset($this->devicelist[91]['current_values']))
                $this->amplifier[91]->updateFromController($this->devicelist[91], 91);
            
                ////////////////тесттранслятор
            if (isset($this->devicelist[60]) && isset($this->devicelist[60]['current_values']))
                $this->ttranslator->updateFromController($this->devicelist[60], 60);
            
                ///////////////////МШУ
            if(isset($this->mshu) &&  isset($this->devicelist[40]['current_values'])){
                $this->mshu->updateFromController($this->devicelist[40], 40);
            }
            
            if (isset($this->devicelist[30]) && isset($this->devicelist[30]['current_values'])){
                $this->matrix[30]->updateFromController($this->devicelist[30], 30);
            }
            if (isset($this->devicelist[31]) && isset($this->devicelist[31]['current_values'])){
                $this->matrix[31]->updateFromController($this->devicelist[31], 31);
            }
            
                
        }
        else{ //////////////Если контроллер недоступен, то пробуем управлять нашими устройствами по командам циклограммы

        }
    }

    public function correct_paramtrs($params = NULL)
    {
        if ($params != NULL and empty($params) == false) {
            for ($i = 0; $i < count($params); $i ++) {
                foreach ($params[$i] as $param_id => $param_value);
                $params_corrected[$i] = $param_value;
            }
            return $params_corrected;
        } // ////////if($params != NU
        else
            return NULL;
    }
    
    
    /*
     * Отдаем конфигурацию арма для вновь подключенных и по требовнию
     */
    private function armConfig($arm_id=1){
        /* это пока убираем, потом будем доставать конфигурацию арма
        echo 'reading config from DB...........' . "\r\n";
        $sql = "SELECT `id`, `config` FROM `arm_config` ";
        if ($this->pdo_conn instanceof PDO) {
            try {
                $rez = $this->pdo_conn->query($sql);
            } catch (PDOException $e) {
                print_r($e);
            }
            if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
                foreach ($rez as $row) {
                    $config[$row['id']] = array(
                        'id' => $row['id'],
                        'config' =>unserialize($row['config']),
                        );
                }
                return $config;
            }
        }
        */
        echo 'arm conf.....'."\n\r";
        $conf_data = array(
        'AntennaSystem'=>array(
            'signal_lvl'=>array('unit'=>'db'),
            'azimut'=>array('unit'=>'deg'),
            'angle'=>array('unit'=>'deg'),
            'scanner'=>array('value_list'=>array(
                '0' => 'Off',
                '1' => 'On'
            )),
            'workmode'=>array('value_list'=>array(
                '0' => 'Auto',
                '1' => 'Programm'
            )),
        ),
            'Amplifier'=>$this->amplifier[90]->getUnitsData(),
            'AmplifierRedundancy'=>$this->amplifier[90]->redundancyUnit->getUnitsData(),
            'UpConverter'=>$this->upconv[10]->getUnitsData(),
            'DownConverter'=>$this->dnconv[20]->getUnitsData(),
            'Ttranslator'=>$this->ttranslator->getUnitsData(),
            'MSHUDevice'=>$this->getMSHUDeviceConfig(),  // $this->getMSHUDeviceData(),//mshu->getUnitsData(),
            'Matrix'=>$this->matrix[30]->getUnitsData(),
            );
        return $conf_data;
    }
    

    private function getMSHUDeviceConfig(){
        $originaldata = $this->mshu->getUnitsData();

        $MinCurrentA1 = $originaldata['MinCurrentA1'];
        unset ($originaldata['MinCurrentA1']);
        unset ($originaldata['MinCurrentA2']);
        $originaldata['MinCurrent'] = $MinCurrentA1;
        
        $MaxCurrentA1 = $originaldata['MaxCurrentA1'];
        unset ($originaldata['MaxCurrentA1']);
        unset ($originaldata['MaxCurrentA2']);
        $originaldata['MaxCurrent'] = $MaxCurrentA1;
        
        $CurrentA1 = $originaldata['CurrentA1'];
        unset ($originaldata['CurrentA1']);
        unset ($originaldata['CurrentA2']);
        $originaldata['Current'] = $CurrentA1;
        

        
        
        return $originaldata;
        
    }
    
    
    private function getMSHUDeviceData()
    {
        $originaldata = $this->mshu->getJsonData()['deviceParameters'];
        
        //print_r($originaldata);
        //exit();
        
        return array(
            'deviceParameters' => array(
                
                'AnyAmplifierAlarm' => array(
                    'nameParameter' => 'AnyAmplifierAlarm',
                    'valueParameter' => $originaldata['AnyAmplifierAlarm']['valueParameter']
                ),
                'AnySwitchAlarm'=>array(
                    'nameParameter' => 'AnySwitchAlarm',
                    'valueParameter' => $originaldata['AnySwitchAlarm']['valueParameter']
                ),
                'AnyPowerSupplyAlarm'=>array(
                    'nameParameter' => 'AnyPowerSupplyAlarm',
                    'valueParameter' => $originaldata['AnyPowerSupplyAlarm']['valueParameter']
                ),
                'AmplifierAlarm'=>array(
                    'nameParameter' => 'AmplifierAlarm',
                    'valueParameter' => $originaldata['AmplifierAlarm']['valueParameter']
                ),
                'ControlMode'=>array(
                    'nameParameter' => 'ControlMode',
                    'valueParameter' => $originaldata['ControlMode']['valueParameter']
                ),
                'Mode'=>array(
                    'nameParameter' => 'Mode',
                    'valueParameter' => $originaldata['Mode']['valueParameter']
                ),
                'Status'=>array(
                    'nameParameter' => 'Status',
                    'valueParameter' => $originaldata['Status']['valueParameter']
                ),
                
               
                'Amplifiers' => array(
                    //////////////
                    1 => array(
                        'AmplifierAlarm' => array(
                            'nameParameter' => 'AmplifierOneAlarm',
                            'valueParameter' => $originaldata['AmplifierOneAlarm']['valueParameter']
                        ),
                        'MinCurrent' => array(
                            'nameParameter' => 'MinCurrentA1',
                            'valueParameter' => $originaldata['MinCurrentA1']['valueParameter']
                        ),
                        'MaxCurrent'=>array(
                            'nameParameter' => 'MaxCurrentA1',
                            'valueParameter' => $originaldata['MaxCurrentA1']['valueParameter']
                        ),
                        'Current'=>array(
                            'nameParameter' => 'CurrentA1',
                            'valueParameter' => $originaldata['CurrentA1']['valueParameter']
                        ),
                    ),
                    2 => array(
                        'AmplifierAlarm' => array(
                            'nameParameter' => 'AmplifierTwoAlarm',
                            'valueParameter' => $originaldata['AmplifierTwoAlarm']['valueParameter']
                        ),
                        'MinCurrent' => array(
                            'nameParameter' => 'MinCurrentA2',
                            'valueParameter' => $originaldata['MinCurrentA2']['valueParameter']
                        ),
                        'MaxCurrent'=>array(
                            'nameParameter' => 'MaxCurrentA2',
                            'valueParameter' => $originaldata['MaxCurrentA2']['valueParameter']
                        ),
                        'Current'=>array(
                            'nameParameter' => 'CurrentA2',
                            'valueParameter' => $originaldata['CurrentA2']['valueParameter']
                        ),
                    )
                    /////////////
                )
            ),
            'title' => 'УН МШУ'
        );
        
    }
    
    

    // //////

    /**
     * Метод для опроса контроллера женька на получение списка устройств
     */
    private function queryArmController()
    {
        // https://sergeyzhuk.me/2017/06/24/reactphp-chat-client/
        // https://sergeyzhuk.me/2017/06/22/reactphp-chat-server/
        $dstip = "10.10.0.162";
        $dstport = "1700";
        echo 'loading device list from controller...........' . "\r\n";

        $loop = React\EventLoop\Factory::create();
        $conn = new React\Socket\Connector($loop);
        $connector = new React\Socket\TimeoutConnector($conn, 1.0, $loop);
        //https://github.com/reactphp-legacy/socket-client#timeoutconnector
        $connector->connect($dstip . ':' . $dstport)->then(function (React\Socket\ConnectionInterface $connection) use ($loop) {
            $cpr = new ContProtocol();
            $cpr->Controller105Prepare();
            $connection->write($cpr->byteHeader);
            $connection->on('data', function ($data) use ($cpr, $connection, $loop) {
                $hundred_arr = null;
                if($cpr->readMessage($data)==true){ // //////////////Получили список устройств, обслуживаемых контроллером
                //$cpr->readMessage($data);
                if (count($cpr->pars) > 0){
                    for ($i = 0; $i < $cpr->pars[0][3]; $i ++) {
                        foreach ($cpr->pars[($i * 5) + 5] as $partype => $status);
                        foreach ($cpr->pars[($i * 5) + 2] as $partype => $type);
                        if ($status != 2 and $type != 21 ) {
                            foreach ($cpr->pars[($i * 5) + 1] as $partype => $UnitNum);
                            foreach ($cpr->pars[($i * 5) + 2] as $partype => $UnitType);
                            foreach ($cpr->pars[($i * 5) + 3] as $partype => $UnitMark);
                            echo '$UnitNum ='.$UnitNum.'| $UnitType ='.$UnitType.'| $UnitMark = '.$UnitMark.'| $status ='.$status."\n\r";
                            $cp = new ContProtocol();
                            if($type == 130)$cp->hdr['CommandNum'][2] = 23; ///////////Матрица
                            else $cp->hdr['CommandNum'][2] = 100; /////////////////////Остальные
                            $cp->hdr['UnitType'][2] = $UnitType;
                            $cp->hdr['UnitMark'][2] = $UnitMark;
                            $cp->hdr['UnitNum'][2] = $UnitNum;
                            $cp->getHdrByte();
                            $cp->pars = NULL;
                            $cp->finalizeMassage();
                            $hundred_arr[] = $cp->byteHeader;
                        }
                    }
                }

                }//////// if($cpr->readMessage($data)==true){ // /////
                $loop->stop();
                $connection->end();
                $loop = null;
                $this->hundred_arr = $hundred_arr;
                echo 'done loading device list from controller' . "\r\n";
            });
        }, function (Exception $exception) use ($loop) {
            echo "Cannot connect to controller: " . $exception->getMessage();
            echo "\r\n";
            $loop->stop();
        });

        $loop->run();
    }
    
    
    
    /**
     * @return Метод для возврата статуса выполняющейся циклограммы
     */
    /*
    private function getCyclogramStatus(){
        if($this->cyclogram_execution_list!=null){
            
            $coms = array();
            if(isset($this->cyclogram_execution_list['command'])){
                foreach ($this->cyclogram_execution_list['command']  as $dev_num => $comlist) {
                    //$coms[$comlist['NumInCicl']]=$comlist['status'];
                    foreach ($comlist as $NumInCicl=>$val) {
                        $coms[$NumInCicl]=$val['status'];
                    }
                }
            }
            
            return array(
                'id'=>$this->cyclogram_execution_list['cyclogram_id'],
                'name'=>$this->cyclogram_execution_list['cyclogram_name'],
                'statuses'=>$coms
                
            );
            
        }
    }
    */
    
    
    ////////////Выполнение команд циклограммы или циклограммы целиком
    private function processCyclogramExecutionCommand($command, $clientid){
        //////////////Выполнение одиночнок комманды
        $command_list=null;
        
        ///////////Команда на сохранение/копирование/удаление/переимен исполнена в web скрипте, тут нужно только перечитать из БД и выкинуть в websocket
        if(isset($command->executionType) && ($command->executionType=='saveCommand' || $command->executionType=='addCommand' || $command->executionType=='copyCyclogram' || $command->executionType=='deleteCommand' || $command->executionType=='deleteCyclogram' || $command->executionType=='renameCyclogram')){
            $this->readCyclogramsFromDB();
                $data['cyclogram'] = $this->cyclogramData;
                foreach ($this->clients as $client) {
                    if(isset($client->clientid) && $clientid==$client->clientid){
                        $client->send(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
                    }
                }
        }

        
        if(isset($command->executionType) && $command->executionType=='singleCommand'){
            ////////////////тут сразу нужно создать какой то статус, который в итоге должен лететь в общей простыне, и говорить о том что команда 
            //////////////// находится в процессе исполнения / исполнена / завершилась с ошибкой
            echo "Одиночная команда циклограммы\n\r";
            $command_list[0]=(array)$command->command;
            echo "command_list:\n\r";
            print_r($command_list);
            $command->executionType='fullCyclogram';
        }
       if(isset($command->executionType)  && $command->executionType=='fullCyclogram') {//////////////Всю циклограмму
            echo 'Исполняем циклограмму:'."\n\r";   
                print_r($command);
            echo "<br>";
            if(isset($command->cyclogram_id) && $command->cyclogram_id>0){
                $cyclogram_execution_list['cyclogram_name']=$command->cyclogram_name;
                $cyclogram_execution_list['cyclogram_id']=$command->cyclogram_id;
                if($command_list==null) $command_list = $this->getCyclogram($command->cyclogram_name);
    
                if($command_list!=null){
                    //$cyclogram_execution_list = null;
                    foreach ($command_list as $record) if(isset($record['UnitType']))  {//////////////Делаем бинарники для контроллера
                        
                        //print_r($record);
                        //continue;
                        
                        $cp = new ContProtocol();
                        $time = microtime();
                        $time_parts = explode(" ", $time);
                        $cp->hdr['T_com_for_kvit'][2] = mktime(22,20,0,9,8, 1974);
                        $cp->hdr['T_create_ticks'][2] =mktime(22,20,0,9,8, 1974);
                        $cp->hdr['CommandNum'][2] = $record['CommandNum']; 
                        $cp->hdr['UnitType'][2] = $record['UnitType']; 
                        $cp->hdr['UnitMark'][2] = $record['UnitMark']; 
                        $cp->hdr['UnitNum'][2] = $record['UnitNum']; 
                        $cp->hdr['ParCount'][2]=$record['CountPar'];
                        $cp->getHdrByte();
                        
                        if($record['CountPar']) $pars = NULL;
                        elseif($record['CountPar']==1){
                            $val = (explode(';', $record['Params']));
                            $pars[0]['type']=array(4, 'int', 3);
                            $pars[0]['value']=array(8, 'double', $val[0]);
                        }
                        elseif($record['CountPar']==2){
                            $val = (explode(';', $record['Params']))[0];
                            $pars[0]['type']=array(4, 'int', 3);
                            $pars[0]['value']=array(8, 'double', $val[0]);
                            
                            $pars[1]['type']=array(4, 'int', 3);
                            $pars[1]['value']=array(8, 'double', $val[1]);
                        }
                        if(isset($pars) && $pars!=null){
                            $cp->pars = $pars;
                            $cp->getParsByte();
                        }
                        $cp->finalizeMassage();
                        //$cyclogram_arr[] = $cp->byteHeader;
                        $record['status']="0";
                        $cyclogram_execution_list['command'][$record['UnitType']][$record['UnitNum']][$record['NumInCicl']]=$record;
                        $cyclogram_execution_list['bincommand'][$record['NumInCicl']]=$cp->byteHeader;
                    }
                    if($cyclogram_execution_list!=null){
                        //print_r($cyclogram_execution_list);
                        //$this->cyclogram_execution_list = $cyclogram_execution_list;
                        $this->exucuteEmulatorCommands($cyclogram_execution_list, $clientid);
                        
                        
                        
    
                    }
                }
            }
            else {
                echo "не передан идентификатор циклограммы \n\r";
            }
        }
    }
    
    
    /** Метод для исполнения циклограммы на своих устройствах
     * @param unknown $cyclogram_execution_list список комманд циклограммы на исполнение
     * @param unknown $clientid идентификатор клиента вебсокета, куда будут лететь статусы об исполнении строк
     */
    private function exucuteEmulatorCommands($cyclogram_execution_list, $clientid){
        if($cyclogram_execution_list!=null){
            $execution_result=null;///////какаплеваме построчные статусы
            //////////Матрица
            //if(isset($this->cyclogram_execution_list['command'][130])){ /////Матрица
            foreach ($cyclogram_execution_list['command'] as $dev_type=>$command_by_dev) {
                
                foreach ($command_by_dev as $device_id=>$command) {
                    foreach ($command as  $key => $command_details) {
                        if($dev_type==130) $this->matrix[$device_id]->updateSingleCommand($command_details); /////////Матрица
                        if($dev_type==60) $this->upconv[$device_id]->updateSingleCommand($command_details); /////////Конвернт вниз
                        if($dev_type==61) $this->dnconv[$device_id]->updateSingleCommand($command_details); /////////Конвернт вверх
                        if($dev_type==63) $this->ttranslator->updateSingleCommand($command_details);  ///////////////Тетстранслятор
                        if($dev_type==210) $this->amplifier[$device_id]->updateSingleCommand($command_details); //////Усилитель мощности
                        if($dev_type==140 && $command_details['UnitMark']==111) $this->mshu->updateSingleCommand($command_details); /// МШУ
                        if($dev_type==140 && $command_details['UnitMark']==110) $this->convredunt[$device_id]->updateSingleCommand($command_details); //резерв конвенторов
                        
                        $execution_result[$command_details['NumInCicl']]=2; /////////2 - выполнено, 1 - невыполнено
                        $data['cyclogramstatus']=array(
                            'id'=>$cyclogram_execution_list['cyclogram_id'],
                            'name'=>$cyclogram_execution_list['cyclogram_name'],
                            'exucution_status'=>'running',
                            'statuses'=>$execution_result
                        );
                        ////Может тут как то вслиять на общий loop, его запускать и на каждой его итерации ему подктдывать строку ? TODO
                        foreach ($this->clients as $client) {
                            if(isset($client->clientid) && $client->clientid==$clientid){
                                $client->send(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
                            }
                        }
                        //sleep(0.2);
                    }
                }
            }
            $data=null;
            $data=array('cyclogramstatus'=>array(
                'id'=>$cyclogram_execution_list['cyclogram_id'],
                'name'=>$cyclogram_execution_list['cyclogram_name'],
                'exucution_status'=>'finished',
                'statuses'=>$execution_result
            )
            );
            foreach ($this->clients as $client) {
                if(isset($client->clientid) && $client->clientid==$clientid){ 
                    $client->send(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
                }
            }
            $this->cyclogram_execution_list = null;
            /////////выполнили
            
        }
    }


    
    private function readCyclogramsFromDB(){
        
        ///////////////////Смотрим какие таблицы есть в БД, что бы потом вытаскивать циклограммы, т.к. в таблице j400_ciclogramms
        ///////////////////могут быть записаны те, которых вообще нет физически
        $this->db_table_list = array();
        $sql="SHOW TABLES";
        if ($this->pdo_conn instanceof PDO) {
            try {
                $rez = $this->pdo_conn->query($sql);
            } catch (PDOException $e) {
                print_r($e);
            }
            if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
                foreach ($rez as $row) {
                    
                    //print_r($row);
                    //echo "\n\r";
                    $this->db_table_list[]=$row['0'];
                    
                }
            }
        }
        
        
        ////////////////Вытасскиваем список циклограмм:$res=Yii::app()->db2->createCommand('select * from J300_ZS_Ciclogramms');
        //echo "readCyclogramsFromDB\n\r";
        $this->cyclogramData=null;
        $sql = 'SELECT
                ID,
                CKG_Name,
                CKG_Date,
                CKG_Description,
                CKG_ID,
                READONLY
                FROM j400_ciclogramms';
        if ($this->pdo_conn instanceof PDO) {
            try {
                $rez = $this->pdo_conn->query($sql);
            } catch (PDOException $e) {
                print_r($e);
            }
            if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
                foreach ($rez as $row) {
                    if(in_array(strtolower($row['CKG_Name']), $this->db_table_list)){
                        
                        $this->cyclogramData['list'][$row['ID']] = array(
                            'id' => $row['ID'],
                            'name' => $row['CKG_Name'],
                            'created'=> $row['CKG_Date'],
                            'descr' => $row['CKG_Description'],
                            'readonly'=>$row['READONLY'],
                            'cyclodata'=>$this->getCyclogram($row['CKG_Name'])
                        );
                        
                        
                    }
                }
                
                
                //$this->cyclogramData['devicelist'] = $this->devicelist;
                /*
                 $this->commandData[$row['P_MARKA_ID']][$row['P_TYPEEQUIP_ID']][$row['NUMCMD']]=array('NUMCMD'=>$row['NUMCMD'],
                 'NAMECMD'=>$row['NAMECMD'], 'NAME_DEVICE'=>$row['NAME_DEVICE']);
                 * */
                foreach ($this->devicelist as $devNum=>$dev) {
                   // echo "num: ".$devNum;
                    //print_r($dev);
                    //echo "\n\r";
                    $this->cyclogramData['devlist'][$devNum]=array(
                        'NameEq'=>$dev['NameEq'],
                        'comlist'=>$this->getDeviceCommand($dev['nType'], $dev['nMarka']),
                    );
                }
               // print_r($this->cyclogramData['devlist']);
                
                
            }
        }
        //print_r($this->cyclogramData);
        //echo "\n\r";
    }
    
    
    
    /** Вытаскиваем комманды для устройства по типу/марке
     * @param unknown $type
     * @param unknown $marka
     */
    private function getDeviceCommand($type, $marka){
        if(isset($this->commandData[$marka]) && isset($this->commandData[$marka][$type])) return $this->commandData[$marka][$type];
        else return null;
    }
    
    
    /////////////////Вытаскиваем содержимое циклограммы
    private function getCyclogram($cycloname){
       // print_r($cycloname);
        //echo "\n\r";
        //j400_typeequip.NAME as unit_type,
        
        $cyclo = null;
        $sql = "SELECT
        $cycloname.NumInCicl, 
        $cycloname.UnitNum,
        $cycloname.CommandNum,
        $cycloname.UnitType,
        $cycloname.UnitMark,
        j400_equipment.NameEq as unit_type,
        $cycloname.TimeOut, 
        $cycloname.CountPar, 
        $cycloname.Params
        FROM $cycloname 
        JOIN j400_typeequip ON j400_typeequip.ID =   $cycloname.UnitType
        JOIN  (SELECT  nType, nMarka, NameEq, LocalNum FROM j400_equipment) j400_equipment ON 
            j400_equipment.nType = $cycloname.UnitType
            AND j400_equipment.nMarka = $cycloname.UnitMark AND j400_equipment.LocalNum=$cycloname.UnitNum
        ORDER BY $cycloname.NumInCicl

        ";
        //print_r($sql);
        //echo "\n\r";
        try {
            $rez = $this->pdo_conn->query($sql);
        } catch (PDOException $e) {
            print_r($e);
        }
        if (isset($rez) && $rez != null && $rez->rowCount() > 0) {
            //print_r($rez);
            //echo "\n\r";
            //return $rez;
            foreach ($rez as $row ) {
                $retval['NumInCicl']=$row['NumInCicl'];
                $retval['UnitNum']=$row['UnitNum'];
                $retval['CommandNum']=$row['CommandNum'];
                $retval['UnitType']=$row['UnitType'];
                $retval['UnitMark']=$row['UnitMark'];
                $retval['unit_type']=$row['unit_type'];
                $retval['TimeOut']=$row['TimeOut'];
                $retval['CountPar']=$row['CountPar'];
                $retval['Params']=$row['Params'];
                $retval['command_txt']=@$this->commandData[$row['UnitMark']][$row['UnitType']][$row['CommandNum']]['NAMECMD'];
     
                $cyclo[]=$retval;
            }
            return $cyclo;
            
        }
        
    }
    
    
    
}

?>