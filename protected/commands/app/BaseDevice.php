<?php
namespace MyApp;

use PDO;
use PDOException;

class BaseDevice
{
    var $id;
    var $jslabel = 'BaseDevice';
    var $name;
    protected $current_values;
    var $old_values;
    private $pdo_conn;
    
    
    
    public function __construct($pdo_conn, $id, $show_error=false)
    {
       // echo 'constructor in BD'."\n\r";
       // echo  $this->jslabel."\n\r";
        if ($pdo_conn instanceof PDO) {
            $this->pdo_conn = $pdo_conn;
        }
        else {
            if($show_error==true)echo 'PDO object received from WSSoketServer was broken'."\n\r";
        }
        $this->id = $id;
    }
    
    public $params=array();
    
    /**Метод для обновления параметров из объекта контроллера
     * @param unknown $cobject
     */
    public function updateFromController($cobject, $deviceId){
        //print_r($cobject);
        $this->id = $deviceId;
        //echo $deviceId.": \n\r";
        foreach ($this->params as $param_id=>$param) {
            $cp=array();
            if(isset($cobject['current_values'][$param_id])) {
                
                $np = $param['label'];
                $v = $cobject['current_values'][$param_id];
                $vp = isset($param['devider'])?$v/$param['devider']:$v; /////////////Делитель значения
                $cp = array('nameParameter'=>$np,'valueParameter'=>$vp);
                if(isset($param['val_list']))  $cp['val_list'] = $param['val_list'];
                
                ////////////////////////////////////Этот код еще не тестен (на 26.02.2021 11:41)
                ////если а параметрах модели устройства есть записи типа $pattern = "0?1:2";
                if(isset($param['pattern'])){
                    eval('$v=('."{$cobject['current_values'][$param_id]}=={$param['pattern']}".');');
                }
                
                if(isset($param['parent'])){
                    $this->current_values[$param['name']."_".$param['parent']]=$cp;
                }
                else $this->current_values[$param['name']]=$cp;
                
                
            }
            $this->jslabel = $cobject['NameEq'];
        }
        
        //print_r($this->current_values);
        // echo "\r\n";
    }
    
    /**
     * Возвращаем данные для передачи в json на html страницу
     */
    public function getJsonData() {
        if(isset($this->current_values) && $this->current_values!=null && is_array($this->current_values)){
            return array(
                'deviceParameters'=>$this->current_values, 'title' => $this->jslabel.' '. $this->id, 'id'=>$this->id);
        }
        else {
            $this->current_values = $this->getDefaultValues();
            return array('deviceParameters'=> $this->current_values, 'title' => $this->jslabel.' '. $this->id, 'id'=>$this->id);
        }
            
        
    }
    
    
    /**
     * Метод для возврата конфигурационных данных о ед.измерения и списках значений параметров устройства
     * Вместо того что бы в базе все это хранить
     */
    public function getUnitsData(){
        $conf_data = array();
        foreach ($this->params as $param_id=>$param) {
            $np=$param['name'];
            if(isset($param['parent'])) $np.="_".$param['parent'];
            if(isset($param['unit']))$conf_data[$np]['unit']=$param['unit'];
            if(isset($param['val_list']))$conf_data[$np]['value_list']=$param['val_list'];
        }
        /*
        if(isset($this->redundancyUnit) && $this->redundancyUnit!=null){
            
        }
        */
        
        if(empty($conf_data)==false) return $conf_data;
        else return null;
    }
    
   
    /** Метод для возврата дефолтных значений
     * @param boolean $zero_values //////////// верныть нули, по умолчанию нет false
     * @return unknown|boolean[]|number[]|unknown[]
     */
    public function getDefaultValues($zero_values=false){
        
        
        foreach ($this->params as $param_id=>$param) {
            
            /*
            echo $param_id.": ";
            print($param['name']).", p=".@$param['parent'];
            echo "\n\r";
            */
            if(isset($param['name'])){
                $np=$param['name'];
                if(isset($param['parent'])) $np.="_".$param['parent'];
                
                if($zero_values==false) {///////Если нужны ненулевые значения
                    
                    
                    ////////////Если задан процент изменения  и было ранее установлено значение
                    if(isset($param['fluctuance']) && isset($this->old_values[$np]) && $this->old_values[$np]['valueParameter']<>0){
                        $dif = $this->old_values[$np]['valueParameter']*$param['fluctuance'];
                        $sign=rand(0,1);
                        $v=($sign==0)?round($this->old_values[$np]['valueParameter']+$dif):round($this->old_values[$np]['valueParameter']-$dif);
                        if(isset($param['limits'])){ ////////Если значение вышло за пределы, то возвращаем его назад
                            if($v>$param['limits']['max'] || $v<$param['limits']['min']) $v= $this->old_values[$np]['valueParameter'];
                        }
                    }
                    //////////////////Если заданы пределы для рандомных значений
                    elseif(isset($param['limits'])){
                       // print_r($param['limits']);
                       // echo ' | ';
                        $v=rand($param['limits']['min'],$param['limits']['max']);
                       // echo $v." | ";
                    }
                    else $v=rand(0,9999999);/////////////Полный рандом
                    
                    if(isset($param['pattern'])){ //////////Это если а параметрах модели устройства есть записи типа $pattern = "0?1:2";
                        eval('$v=('."{$v}=={$param['pattern']}".');');
                    }
                   // echo 'v = '.$v."\n\r";
                }
                else { ///////////////Для зануления
    
                    $v=0;
                }
                
                $current_values[$np]=array(
                    'nameParameter'=>$param['label'],
                    'valueParameter'=>isset($param['type'])&&$param['type']=='bool'?(bool)rand(0,1):$v
                );
                if(isset($param['val_list']))  {
                    //$current_values[$param['name']]['val_list'] = $param['val_list'];
                    //$current_values[$param['name']]['valueParameter'] = $param['val_list'][0];
                }
            }
            
            
        }
        
        $this->old_values = $current_values;
        return $current_values;
        
    }
    
    
    /** Метод для обновления параметров устройства. Нужно как то сделать что бы не было рандома
     * @param unknown $devicedata
     */
    public function updateState($devicedata){
        //print_r($devicedata);
        //echo "\n\r";
        foreach ($devicedata as $parametrName=>$parametr) {
            //echo $parametrName.": ".$parametr->valueParameter."\n\r";
            $this->current_values[$parametrName]['valueParameter']=$parametr->valueParameter;
        }
        
        
        
    }
}
?>
