<?php
namespace MyApp;

use PDO;
use PDOException;

class mitec163995_ttranslator extends BaseDevice
{
    public $params=array(
        '0'=>array('name'=>'dac', 'label'=>'dac'),
        '1'=>array('name'=>'ImpedanceValue', 'label'=>'Сопротивление', 'val_list'=>array(1=>'50', 0=>'75'), 'unit'=>'ohm'),
        '2'=>array('name'=>'MuteState', 'label'=>'Заглушен', 'val_list'=>array(1=>'UnMute', 2=>'Mute'), 'limits'=>array('min'=>1, 'max'=>2)),
        '3'=>array('name'=>'slope', 'label'=>'Slope'),
        '4'=>array('name'=>'OutputFrequency', 'label'=>'Вых. частота', 'unit'=>'Hz'),
        '8'=>array('name'=>'InputFrequency', 'label'=>'Вх. частота', 'val_list'=>array(0=>'70', 1=>'140'), 'unit'=>'MHz'),
        '5'=>array('name'=>'AttenuationValue', 'label'=>'Значение ослабления', 'unit'=>'db', 'limits'=>array('min'=>0, 'max'=>50)),
        '7'=>array('name'=>'ControlMode', 'label'=>'Режим управления', 'limits'=>array('min'=>0, 'max'=>1), 'val_list'=>array(0=>'local', 1=>'remote')),
        '12'=>array('name'=>'UserTestAlarm', 'label'=>'Тревога пользователя', 'limits'=>array('min'=>0, 'max'=>1)),
        '13'=>array('name'=>'LoggedAlarm', 'label'=>'Тревога журнала ', 'limits'=>array('min'=>0, 'max'=>1)),
        '14'=>array('name'=>'LocalOscillatorLockAlarm', 'label'=>'LocalOscillatorLockAlarm', 'limits'=>array('min'=>0, 'max'=>1)),
        '15'=>array('name'=>'PowerSupplyAlarm', 'label'=>'Тревога БП ', 'limits'=>array('min'=>0, 'max'=>1)),
        '16'=>array('name'=>'LocalOscillatorLevelAlarm', 'label'=>'LocalOscillatorLevelAlarm', 'limits'=>array('min'=>0, 'max'=>1)),
        '17'=>array('name'=>'AmlifierCurrentAlarm', 'label'=>'Тревога тока усилителя', 'limits'=>array('min'=>0, 'max'=>1)),
        '18'=>array('name'=>'ExternalAlarm', 'label'=>'Внешняя тревога', 'limits'=>array('min'=>0, 'max'=>1)),
        '19'=>array('name'=>'TemperatureAlarm', 'label'=>'Тревога по температуре', 'limits'=>array('min'=>0, 'max'=>1)),
        '20'=>array('name'=>'ModuleCommunicationsAlarm', 'label'=>'Тревога модуля связи', 'limits'=>array('min'=>0, 'max'=>1)),
        '21'=>array('name'=>'Voltage15Positive', 'label'=>'Напряжение +5В', 'limits'=>array('min'=>0, 'max'=>1)),
        '22'=>array('name'=>'Voltage15Negative', 'label'=>'Напряжение -5В', 'limits'=>array('min'=>0, 'max'=>1)),
        '23'=>array('name'=>'Voltage5PositiveBusA', 'label'=>'Напряжение +5В шина А', 'limits'=>array('min'=>0, 'max'=>1)),
        '24'=>array('name'=>'Voltage5PositiveBusB', 'label'=>'Напряжение +5В шина Б', 'limits'=>array('min'=>0, 'max'=>1)),
        
        
    );
    
    
    public function updateSingleCommand($command_details){
        
        if($command_details['CommandNum']==54){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['InputFrequency']['valueParameter'] = $params[0];
            }
        }
        if($command_details['CommandNum']==31){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['AttenuationValue']['valueParameter'] = $params[0];
            }
        }
        if($command_details['CommandNum']==63){
            
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                $this->current_values['MuteState']['valueParameter'] = $params[0];
            }
            
        }
        
        
    }
    
}
?>
