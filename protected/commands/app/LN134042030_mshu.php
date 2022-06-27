<?php
/////////////////МШУ
namespace MyApp;

use PDO;
use PDOException;

class LN134042030_mshu extends BaseDevice
{
    public $params=array(
        '9'=>array('name'=>'AnyAmplifierAlarm', 'label'=>'AnyAmplifierAlarm', 'limits'=>array('min'=>0, 'max'=>1)),//Неисправность МШУ (Fault. Any AmplifierPower Alarm)
        '10'=>array('name'=>'AnySwitchAlarm', 'label'=>'AnySwitchAlarm', 'limits'=>array('min'=>0, 'max'=>1)),//Неисправность переключателя (Fault. Any Switch Alarm)
        '11'=>array('name'=>'AnyPowerSupplyAlarm', 'label'=>'AnyPowerSupplyAlarm', 'limits'=>array('min'=>0, 'max'=>1)),//Неисправность питания (Fault. Any Power Supply Alarm)
        '12'=>array('name'=>'AmplifierOneAlarm', 'label'=>'Тревога усилитель 1', 'limits'=>array('min'=>0, 'max'=>1)),//Неисправность МШУ1 (Fault. A1)
        '13'=>array('name'=>'AmplifierTwoAlarm', 'label'=>'AmplifierTwoAlarm',  'limits'=>array('min'=>0, 'max'=>1)),//Неисправность МШУ2 (Fault. A2)
        '14'=>array('name'=>'AmplifierAlarm', 'label'=>'AmplifierAlarm',  'limits'=>array('min'=>0, 'max'=>1)),//Неисправность переключателя или питания или МШУ (Fault. Summary Alarm)
        '19'=>array('name'=>'ControlMode', 'label'=>'Режим управления', 'limits'=>array('min'=>0, 'max'=>1), 'val_list'=>array(0=>'local', 1=>'remote')), ///////////Режим кправления
        '20'=>array('name'=>'Mode', 'label'=>'ManagmentMode', 'limits'=>array('min'=>0, 'max'=>1), 'val_list'=>array(0=>'Auto', 1=>'Manual') ),///////////ManagmentMode
        '21'=>array('name'=>'Status', 'label'=>'Статус резервирования', 'limits'=>array('min'=>1, 'max'=>2), 'val_list'=>array(1=>'GeneralUnit', 2=>'ReserveUnit')), /////////Ссостояние резерва
        '15'=>array('name'=>'MinCurrentA1', 'label'=>'MinCurrentA1','unit'=>'A',  'limits'=>array('min'=>0, 'max'=>10)),///текущие значение предельных токов
        '16'=>array('name'=>'MaxCurrentA1', 'label'=>'MaxCurrentA1','unit'=>'A',  'limits'=>array('min'=>0, 'max'=>10)),///текущие значение предельных токов
        '17'=>array('name'=>'MinCurrentA2', 'label'=>'MinCurrentA2', 'unit'=>'A', 'limits'=>array('min'=>0, 'max'=>10)),///текущие значение предельных токов
        '18'=>array('name'=>'MaxCurrentA2', 'label'=>'MaxCurrentA2', 'unit'=>'A', 'limits'=>array('min'=>0, 'max'=>10)),///текущие значение предельных токов
        '26'=>array('name'=>'CurrentA1', 'label'=>'CurrentA1', 'unit'=>'A', 'limits'=>array('min'=>0, 'max'=>10)), ////////////Ток усилителя 1
        '27'=>array('name'=>'CurrentA2', 'label'=>'CurrentA1', 'unit'=>'A', 'limits'=>array('min'=>0, 'max'=>10)),////////////Ток усилителя 2
        
        
    );
    
    
    public function updateSingleCommand($command_details){
        // print_r($this->current_values);
        // echo "\n\r";
        
        if($command_details['CommandNum']==28){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['Mode']['valueParameter'] = $params[0];
            }
        }
        if($command_details['CommandNum']==31){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['Status']['valueParameter'] = $params[0];
               // if($params[0]==2) $this->current_values['Status']['valueParameter'] = 1;
               // if($params[0]==1) $this->current_values['Status']['valueParameter'] = 0;
            }
        }
        if($command_details['CommandNum']==24){
            $params = explode(';', $command_details['Params']);
            print_r($params);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                // Вх.пар.:  0(int) - Номер усилителя (1..2)
                //           1(int) - Минимальный ток (мА)
                //           2(int) - Максимальный ток (мА)
                if($params[0]==1 && count($params)>=3){
                    $this->current_values['MinCurrentA1']['valueParameter'] = $params[1];
                    $this->current_values['MaxCurrentA1']['valueParameter'] = $params[2];
                }
                elseif($params[0]==2 && count($params)>=3){
                    $this->current_values['MinCurrentA2']['valueParameter'] = $params[1];
                    $this->current_values['MaxCurrentA2']['valueParameter'] = $params[2];
                }
            }
        }

        
        
    }
    
    
    
    
    
    
    
    
}
?>
