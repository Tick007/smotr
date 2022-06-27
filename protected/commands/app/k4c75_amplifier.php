<?php
namespace MyApp;

use PDO;
use PDOException;


/*
  case (int)E_AMPLIFIER_POWER_K4C75_CMND.GET_SWITCH_CONTROLLER_STATUS:
                            ShowData.SwitchStatus = (UMWGSwitchStatus)args[0];
                            ShowData.SwitchIsInAutoMode = (int)args[18] == 1;
                            break;
   [Description("Выдать статус системы переключателей.")]
    [Rus("Выдать статус системы переключателей.")]
    [Eng("Get the status of the system switches.")]
    GET_SWITCH_CONTROLLER_STATUS = 70,
    Это команда 70 должна быть подана, что бы статус переключателей получить
    
      public enum UMWGSwitchStatus
  {
    Spin = 0,    // вращается еще не переключился
    Position1 = 1,
    Position2 = 2 ,
    Error = 3,
    UnknownAfterSwitching = 4,  // неизвестная позиция на схеме отображается как ?
    NoInfo = 5                  // нет данных 
  }
    
 */

class K4c75_redundancy_control  extends BaseDevice{
    
    var $id=0;
    var $jslabel = 'Amplifier Redundancy Control Module';
    
    public  function __construct() {}

    
    public $params=array(
    '0'=>array('name'=>'SwitchStatus', 'label'=>'Положение перключателя', 'limits'=>array('min'=>0, 'max'=>5), 'val_list'=>array(
        0=>'Spin',    // вращается еще не переключился
        1=>'Position1',
        2=>'Position2' ,
        3=>'Error',
        4=>'UnknownAfterSwitching',  // неизвестная позиция на схеме отображается как ?
        5=>'NoInfo'                  // нет данных ),),
    )),
    '18'=>array('name'=>'SwitchIsInAutoMode', 'label'=>'Переключатель в авто режиме',
        'limits'=>array('min'=>0, 'max'=>1), 'val_list'=>array(0=>'Manual',   1=>'Auto')),
      );  
}

class K4c75_amplifier extends BaseDevice
{
    
    public $redundancyUnit=null;
    
    var $jslabel = 'Amplifier';
    public $params=array(
        '0'=>array('name'=>'devstate', 'label'=>'Dev state', 'val_list'=>array(
            0=>'Fault', 
            1=>'PowerOn', 
            2=>'Initialize', 
            3=>'Ready',
            4=>'HeaterTimeDelay',
            5=>'Standby',
            6=>'BeamOnSequence',
            7=>'Transmit',
            8=>'BeamOffSequence',
            9=>'ColdTransmit'
        ), 'limits'=>array('min'=>0, 'max'=>9)),
        '1'=>array('name'=>'ControlPoint', 'label'=>'Control point'),
        '2'=>array('name'=>'PowerSaverOn', 'label'=>'PowerSaver On', 'type'=>'bool'),
        '3'=>array('name'=>'PowerTrackerOn', 'label'=>'PowerTracker On', 'type'=>'bool'),
        '4'=>array('name'=>'ALCOn', 'label'=>'ALC On', 'type'=>'bool'),
        
        '21'=>array('name'=>'power', 'label'=>'Output RF power', 'parent'=>'rf', 'unit'=>'Wt', 'limits'=>array('min'=>0, 'max'=>3000), 'fluctuance'=>0.005),
        '22'=>array('name'=>'voltage', 'label'=>'Beam Voltage', 'parent'=>'beam',  'devider'=>100, 'unit'=>'V'),
        '23'=>array('name'=>'current', 'label'=>'Beam current', 'parent'=>'beam', 'devider'=>100,'unit'=>'A'),
        '24'=>array('name'=>'current', 'label'=>'Body current', 'parent'=>'body', 'unit'=>'A'),
        '25'=>array('name'=>'voltage', 'label'=>'Heater voltage', 'parent'=>'heater', 'devider'=>100, 'unit'=>'V'),
        '26'=>array('name'=>'current', 'label'=>'Heater current', 'parent'=>'heater', 'devider'=>100, 'unit'=>'A'),
        '27'=>array('name'=>'power', 'label'=>'ReflectedRF poower', 'parent'=>'reflectedrf', 'unit'=>'Wt'),
        '28'=>array('name'=>'cabinet', 'label'=>'Temperature cabinet', 'parent'=>'temperature', 'unit'=>'C', 'limits'=>array('min'=>-50, 'max'=>+50)),//28(int)  - температура в шкафу (*C)
        '29'=>array('name'=>'inlet', 'label'=>'Temperature InLet', 'parent'=>'temperature', 'unit'=>'C', 'limits'=>array('min'=>-50, 'max'=>+50), 'fluctuance'=>0.05),//   29(int)  - температура воздуха на входе (*C)
        '30'=>array('name'=>'outlet', 'label'=>'Temperature OutLet', 'parent'=>'temperature', 'unit'=>'C', 'limits'=>array('min'=>0, 'max'=>+50), 'fluctuance'=>0.05),//   30(int)  - температура воздуха на выходе (*C)
        '31'=>array('name'=>'differential', 'label'=>'Temperature differential', 'parent'=>'temperature', 'unit'=>'C', 'limits'=>array('min'=>0, 'max'=>+50)), //   31(int)  - перепад температуры (*C)
        '32'=>array('name'=>'nameplatevoltage', 'label'=>'NamePlate voltage', 'parent'=>'beam', 'unit'=>'V', 'devider'=>100), //   32(int)  - номинальноe напряжениe пучка(1/100 кВ)
        '33'=>array('name'=>'attenuatorpower', 'label'=>'Attenuator power', 'devider'=>10, 'unit'=>'Wt'), //   33(int)  - настройки аттенюатора (1/10 дБ)
        '34'=>array('name'=>'lowalarmvalue', 'label'=>'Low Alarm Value'),//   34(int)  - Запрос отключения по тревоге низкой выходной мощности СВЧ (Вт) 
        '35'=>array('name'=>'highalarmvalue', 'label'=>'HighAlarmValue'),//35(int)  - Запрос отключения по тревоге высокой выходной мощности СВЧ (Вт)
        '36'=>array('name'=>'lowfaultvalue', 'label'=>'Low Fault Value'), //   36(int)  - Запрос отключения по отказу низкой выходной мощности СВЧ (Вт) 
        '37'=>array('name'=>'highfaultvalue', 'label'=>'High Fault Value'),//   37(int)  - Запрос отключения по отказу высокой выходной мощности СВЧ (Вт) 
        '38'=>array('name'=>'standbyvoltagefault', 'label'=>'Standby Voltage Fault', 'parent'=>'beam', 'devider'=>100),//   38(int)  - Запрос уставки отказа напряжения пучка во время ожидания(1/100 кВ)
        //'39'=>array('name'=>'beamlowalarmvalue', 'label'=>'Low Alarm Value', 'devider'=>100),//   39(int)  - Запрос уставки тревоги отключения по низкому напряжению пучка (1/100 кВ)
        //'40'=>array('name'=>'beamfaultvalue', 'label'=>'High Fault Value',  'devider'=>100), //   40(int)  - Запрос уставки отказа отключения по высокому напряжению пучка (1/100 кВ)
        '41'=>array('name'=>'currenttrippointshighfaultvalue', 'parent'=>'body', 'label'=>'CurrentTripPoints.HighFaultValue'),  //   41(int)  - Запрос уставки отказа отключения по повышенному току корпуса (мА)
        '44'=>array('name'=>'currenttrippointslowfaultvalue', 'label'=>'Heater.CurrentTripPoints.LowFaultValue', 'parent'=>'heater', 'devider'=>100), //   44(int)  - Запрос уставки отказа отключения по низкому току нагревателя  (1/100 А)
        '45'=>array('name'=>'currenttrippointshighfaultvalue', 'label'=>'Heater.CurrentTripPoints.HighFaultValue', 'parent'=>'heater', 'devider'=>100),//   45(int)  - Запрос уставки отказа отключения по повышенному току нагревателя (1/100 A)
        '46'=>array('name'=>'highfaulttrippoint', 'label'=>'HighFaultTripPoint', 'parent'=>'reflectedrf'), //   46(int)  - Запрос уставки отказа отключения по высокой отраженной мощности СВЧ (Вт)
        '49'=>array('name'=>'channelnumber', 'label'=>'Channel Number','limits'=>array('min'=>1, 'max'=>2)), //   49(int)  - канал клистрона
        '56'=>array('name'=>'rfinhibit', 'label'=>'RFInhibit', 'type'=>'bool','fluctuance'=>0),//   50(bool) - 56(bool) - запрет несущей 
        '120'=>array('name'=>'alcpower', 'label'=>'ALC Power', 'limits'=>array('min'=>0, 'max'=>3000), 'fluctuance'=>0.005, 'unit'=>'Wt'),
        '121'=>array('name'=>'manualpower', 'label'=>'Manual Power', 'unit'=>'Wt'),
        
       
    );
    
    public function updateSingleCommand($command_details){
                
        if($command_details['CommandNum']==41){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['lowalarmvalue']['valueParameter'] = $params[0];
            }
        }
        
        if($command_details['CommandNum']==42){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['highalarmvalue']['valueParameter'] = $params[0];
            }
        }
        if($command_details['CommandNum']==43){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['lowfaultvalue']['valueParameter'] = $params[0];
            }
        }
        if($command_details['CommandNum']==44){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['highfaultvalue']['valueParameter'] = $params[0];
            }
        }
        if($command_details['CommandNum']==45){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['devstate']['valueParameter'] = $params[0];
            }
        }
        if($command_details['CommandNum']==47){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['ALCOn']['valueParameter'] = $params[0];
            }
        }
        if($command_details['CommandNum']==52){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['alcpower']['valueParameter'] = $params[0];
            }
        }

        if($command_details['CommandNum']==72){ /////////TODO
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                if($this->redundancyUnit!=null){
                    $this->redundancyUnit->current_values['SwitchStatus']['valueParameter'] = $params[0];
                }
            }
        }
        
        if($command_details['CommandNum']==74){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['channelnumber']['valueParameter'] = $params[0];
            }
        }
        
        if($command_details['CommandNum']==75){ ///////////////////////////Запрет несущей
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                $this->current_values['rfinhibit']['valueParameter'] = $params[0];
            }
        }

    
    }
    
}
?>
