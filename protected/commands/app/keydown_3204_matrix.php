<?php
namespace MyApp;

use PDO;
use PDOException;

class keydown_3204_matrix extends BaseDevice
{
    var $jslabel = 'Matrix';
    private $unit_type = 130; //////////////Идентификатор типа устройства
    
    public $params=array(
        '0'=>array(
            'name'=>'Output1', 
            'label'=>'Output 1 - input connection', 
            'fluctuance'=>0.001, 
            'limits'=>array('min'=>0, 'max'=>8)
            ),
        '1'=>array('name'=>'Output2', 'label'=>'Output 2 - input connection', 'fluctuance'=>0.001, 'limits'=>array('min'=>0, 'max'=>8)),
        '2'=>array('name'=>'Output3', 'label'=>'Output 3 - input connection', 'fluctuance'=>0.001, 'limits'=>array('min'=>0, 'max'=>8)),
        '3'=>array('name'=>'Output4', 'label'=>'Output 4 - input connection', 'fluctuance'=>0.001, 'limits'=>array('min'=>0, 'max'=>8)),
        '4'=>array('name'=>'Output5', 'label'=>'Output 5 - input connection', 'fluctuance'=>0.001, 'limits'=>array('min'=>0, 'max'=>8)),
        '5'=>array('name'=>'Output6', 'label'=>'Output 6 - input connection', 'fluctuance'=>0.001, 'limits'=>array('min'=>0, 'max'=>8)),
        '6'=>array('name'=>'Output7', 'label'=>'Output 7 - input connection', 'fluctuance'=>0.001, 'limits'=>array('min'=>0, 'max'=>8)),
        '7'=>array('name'=>'Output8', 'label'=>'Output 8 - input connection', 'fluctuance'=>0.001, 'limits'=>array('min'=>0, 'max'=>8)),
     
    );
    
    
    
    /** LEGACY Метод для обновления параметров устройства по командам циклограмм
     * @param список комманд циклограммы $commands
     */
    public function updateStateCY($commands){
        //echo "updateStateCY";
        //print_r($current_values);
        //print_r($this->current_values);
        //echo "\n\r";
                $execution_result = null;
                foreach ($commands as $key => $command_details) {
                    //print_r($command_details);
                    ///echo "\n\r";
                    if($command_details['UnitType']==$this->unit_type) {
                        
                        if($command_details['CommandNum']==22){////////////Разомкнуть все цепи
                            foreach ($this->params as $key => $value) {
                                //$this=>params[$key];
                                $this->current_values[$value['name']]['valueParameter']=0;
                                
                            }
                        }
                        if($command_details['CommandNum']==20){
                            $params = explode(';', $command_details['Params']);
                            if(is_array($params)==true){
                                $output = 'Output'.$params[1];
                                $this->current_values[$output]['valueParameter'] = $params[0];
                            }
                            //sleep(0.5);
                        }
                        $execution_result[$command_details['NumInCicl']]=2; /////////2 - выполнено, 1 - невыполнено
                    }
               }
            
            //echo "\n\r";

               return $execution_result;
        
    }
    
    public function updateSingleCommand($command_details){
        if($command_details['CommandNum']==22){////////////Разомкнуть все цепи
            foreach ($this->params as $key => $value) {
                //$this=>params[$key];
                $this->current_values[$value['name']]['valueParameter']=0;
                
            }
        }
        if($command_details['CommandNum']==20){ /////////////Замкнуть
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                $output = 'Output'.$params[1];
                $this->current_values[$output]['valueParameter'] = $params[0];
            }
            
        }
        
        if($command_details['CommandNum']==21){ /////////////Разомкнуть
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                $output = 'Output'.$params[0];
                $this->current_values[$output]['valueParameter'] = 0;
            }
            
        }
        
    }
    
 
    
}
?>
