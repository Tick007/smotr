<?php
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Socket;
// include 'app//socket.php';
use MyApp\Antenna;

require dirname(__FILE__) . '/vendor/autoload.php';
include('d:\web\wwwroot\arm\protected\components\ContProtocol.php'); ///////////Протокол контроллера

class WSCommand extends CConsoleCommand
{

    var $server;

    var $clients;

    public $pdo_conn;

    public $antenna;

    public function __construct()
    {
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
    }

    public function actionIndex($c /*, $lvsnumsrc , $lvsnumdst, $dstab, $srcab, $conrollerport*/)	{ // //////////Соединение Арма с контроллером
        $command = $c;

        if ($command == 'start') {
            echo "startFunc\n\r";

            $hook = function () {
                $this->onReceivecallback();
            };

            $server = IoServer::factory(new HttpServer(new WsServer(new Socket($this))), 8081);

            $server->loop->addPeriodicTimer(4, function () use ($server) {
                echo 'livetag ' . date('H:i:s') . "\r\n";
                $this->sendLiveTag();
                $this->getAllParams($this->pdo_conn);
            });

            $server->loop->addPeriodicTimer(2, function () use ($server) {
                // echo 'livetag' . "\r\n";
                $this->send100();
            });
            
            /*
                $server->loop->addPeriodicTimer(3, function () use ($server) {
                    // echo 'livetag' . "\r\n";
                    $this->queryArmController();;
                });
                */

            $this->server = $server;
            $this->server->run();

            echo 'afterrun'; // ////сюда уже не доходит
        }
    }

    public function onReceivecallback(ConnectionInterface $from, $msg, \SplObjectStorage $clients)
    {
        echo 'onReceive: ' . $msg . "\r\n";
        $this->parseMessage($msg);

        $this->clients = $clients;
        foreach ($clients as $client) {
            $client->send("Client $from->resourceId said $msg");
        }
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

        //var_dump($rez);
        if (isset($rez) && $rez!=null && $rez->rowCount() > 0) {
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
        }
        else echo 'no data in DB for antenna table'. "\r\n";
        
    }
    else{
        echo 'broken PDO object in getAllParams'."\n\r";
    }
        
        
    }

    /**
     * отправка метки жизни клиентам
     */
    public function sendLiveTag()
    {
        if (isset($this->clients)) {
            foreach ($this->clients as $client) {
                $livetag = array(
                    'livetag' => date("d.m.Y H:i:s")
                );
                $client->send(json_encode($livetag));
            }
        }
    }

    public function send100()
    {
        if (isset($this->clients)) {
            // $data=array('device_params'=>array('param1'=>rand(50,100), 'param2'=>rand(11,30),'param3'=>rand(31,49)));
            $data = array(
                'DeviceParameters' => array(
                    'antennaDeviceData' => $this->getAntennaData(),
                    'antennaDeviceDataById' =>array( '1'=>$this->getAntennaDataById(1), '2'=>$this->getAntennaDataById(2)),
                    'testTranslyatorDeviceData' => $this->getTestTranslyatorData(),
                    'amplifier1DeviceData1' => $this->getAmplifierData(1),
                    'amplifier1DeviceData2' => $this->getAmplifierData(2),
                    'MSHUDeviceData1' => $this->getMSHUDeviceData(1),
                    'MSHUDeviceData2' => $this->getMSHUDeviceData(2),
                    'upConverterDeviceData1' => $this->getupConverterDeviceData(1),
                    'upConverterDeviceData2' => $this->getupConverterDeviceData(2),
                    'downConverterDeviceData1' => $this->getdownConverterDeviceData(1),
                    'downConverterDeviceData2' => $this->getdownConverterDeviceData(2)
                )
            );
            foreach ($this->clients as $client) {
                $client->send(json_encode($data));
            }
        }
    }

    public function onConnectionListChange(\SplObjectStorage $clients)
    {
        $this->clients = $clients;
    }

    private function parseMessage($param)
    {
        if ($param != null && trim($param != '')) {
            $pieces = explode(':::', $param);
            if (is_array($pieces) == true) {
                if ($pieces[0] == "control")
                    $this->executeControlcommand($pieces[1]);
            }
        } else {
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
            return $this->antenna[$id]->getJsonValues();
        } else
            return null;
    }

    private function getTestTranslyatorData()
    {
        return array(
            'deviceParameters' => array(
                array(
                    'nameParameter' => 'Частота (МГц)',
                    'valueParameter' => rand(3, 50)
                ),
                array(
                    'nameParameter' => 'Ослабление (дБ)',
                    'valueParameter' => rand(- 20, 30)
                ),
                array(
                    'nameParameter' => 'ВЧ выход',
                    'valueParameter' => rand(0, 5)
                )
            ),
            'title' => 'ТЕСТ-ТРАНСЛЯТОР'
        );
    }

    private function getAmplifierData($n)
    {
        return array(
            'deviceParameters' => array(
                array(
                    'nameParameter' => 'Режим',
                    'valueParameter' => (rand(0, 1) == 1 ? 'Токовый' : 'Напряжение')
                ),
                array(
                    'nameParameter' => 'Вых. мощность (Вт)',
                    'valueParameter' => rand(0, 500)
                ),
                array(
                    'nameParameter' => 'Запрет несущей',
                    'valueParameter' => (rand(0, 1) == 1 ? 'вкл' : 'выкл')
                ),
                array(
                    'nameParameter' => 't °C(вх,вых Δ)',
                    'valueParameter' => rand(- 50, 60)
                )
            ),
            'title' => 'УМ #' . $n
        );
    }

    private function getMSHUDeviceData($n)
    {
        return array(
            'deviceParameters' => array(
                array(
                    'nameParameter' => 'Значение тока (мА)',
                    'valueParameter' => rand(0, 1000)
                ),
                array(
                    'nameParameter' => 'Пределы тока (мА)',
                    'valueParameter' => rand(500, 1500)
                ),
                array(
                    'nameParameter' => 'Напряжение A (В)',
                    'valueParameter' => rand(180, 245)
                ),
                array(
                    'nameParameter' => 'Напряжение B (В)',
                    'valueParameter' => rand(190, 235)
                )
            ),
            'title' => 'МШУ #' . $n
        );
    }

    private function getupConverterDeviceData($n)
    {
        return array(
            'deviceParameters' => array(
                array(
                    'nameParameter' => 'Частота (МГц)',
                    'valueParameter' => rand(2500, 4500)
                ),
                array(
                    'nameParameter' => 'Ослабление (дБ)',
                    'valueParameter' => rand(0, 99)
                ),
                array(
                    'nameParameter' => 'ВЧ выход',
                    'valueParameter' => rand(0, 3)
                ),
                array(
                    'nameParameter' => 'Напряжение B (В)',
                    'valueParameter' => rand(190, 235)
                )
            ),
            'title' => 'КОНВЕРТЕР ВВЕРХ #' . $n
        );
    }

    private function getdownConverterDeviceData($n)
    {
        return array(
            'deviceParameters' => array(
                array(
                    'nameParameter' => 'Частота (МГц)',
                    'valueParameter' => rand(2500, 4500)
                ),
                array(
                    'nameParameter' => 'Ослабление (дБ)',
                    'valueParameter' => rand(0, 99)
                )
            ),
            'title' => 'КОНВЕРТЕР ВВЕРХ #' . $n
        );
    }
    
    
    /**
     * Метод для опрома контроллера женька
     */
    private function queryArmController(){
        $dstip = "10.10.0.161";
        $dstport = "1700";
        echo 'cont reqw' . "\r\n";
        $pop_conn  = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp')); //открываем сокет
        if(isset( $pop_conn) and  $pop_conn!=NULL ) {
            $connected = @socket_connect ( $pop_conn  , $dstip,  $dstport  );
            if($connected==false){
                $this->stop = true;
                socket_close($pop_conn);
                unset($this->conn);
                ////////////Шлём сообщение в webсокет сервер, что процесс работы с сокетами завершился
                $this->notifyBebSocketServer("Couldn't connect to controller on line 76\n\r");
                //exit();
                echo "Couldn't connect to ".$dstip." on line 369\n\r";
                return;
            }
            else {
                echo "Couldn't connect to ".$dstip." on line 373\n\r";
            }
        }
    }
    
}

?>