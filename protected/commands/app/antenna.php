<?php
namespace MyApp;

use PDO;
use PDOException;

class Antenna
{

    public $id;

    public $workmode;

    public $azimut;

    public $angle;

    public $scanner;

    public $title;

    private $pdo_conn;

    public function __construct($pdo_conn, $id)
    {
        if ($pdo_conn instanceof PDO) {
            $this->pdo_conn = $pdo_conn;
        }
        else {
            echo 'PDO object received from WSSoketServer was broken'."\n\r";
        }
        $this->id = $id;
    }

    public $modes = array(
        '0' => 'Auto',
        '1' => 'Programm'
    );

    public function getJsonData()
    {
        $signal_lvl = rand(- 15, - 25);
        $azimut = rand(45, 50);
        $angle = rand(15, 20);
        //'val_list'=>array(1=>'50ОМ', 0=>'75Ом')
        $return_data = array('deviceParameters'=>array(
            'azimut' => array('nameParameter'=>'Азимут', 'valueParameter'=>$this->azimut, 'unit'=>'deg'),
            'angle' => array('nameParameter'=>'Угол места', 'valueParameter'=>$this->angle, 'unit'=>'deg'),
            'scanner' => array('nameParameter'=>'Сканнер', 'valueParameter'=>rand(0,1), 'val_list'=>array(
                '0' => 'Off',
                '1' => 'On'
            )),
            'workmode' =>  array('nameParameter'=>'Рабочий режим', 'valueParameter'=>$this->workmode, 'val_list'=>$this->modes, 'pattern'=>array()),
            'title' =>  array('nameParameter'=>'Наименование', 'valueParameter'=>$this->title),
            'id' => array('nameParameter'=>'Идентификатор', 'valueParameter'=>$this->id),
            'signal_lvl' => array('nameParameter'=>'Уровень сигнала', 'valueParameter'=>$signal_lvl, 'unit'=>'db'),
            //'modes' => $this->modes
        ));
        
        
        $sql = "UPDATE `smotr_antenna` SET  signal_lvl='".$signal_lvl."'";  
        
        if ($this->workmode == 0) {
            $return_data['azimut']['valueParameter'] = $azimut;
            $return_data['angle']['valueParameter'] = $angle;
            $sql.=", azimut='" . $azimut . "', angle='" . $angle . "'";
        }
        $sql.=" WHERE id=" . $this->id;
        
        try {
            $rez = $this->pdo_conn->query($sql);
            //var_dump($rez);
        } catch (PDOException $e) {
            print_r($e);
        }
        
        
        
         return $return_data;

    }
}

?>