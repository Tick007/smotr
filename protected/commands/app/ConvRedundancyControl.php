<?php
/////////////////Управление резервом конвертарами
namespace MyApp;

use PDO;
use PDOException;

class ConvRedundancyControl extends BaseDevice
{
    var $jslabel = 'Converter redundancy';
    
    //0.Int32=1,  1.Int32=1,  2.Int32=0,  3.Int32=0,  4.Int32=0,  5.Int32=0,  6.Int32=0,  7.Int32=0,  8.Int32=0,  9.Int32=0,  10.Int32=0,
    //11.Int32=0,  12.Int32=3,  13.Int32=3,  14.Int32=3,  15.Int32=3,  16.Int32=3,  17.Int32=3,  18.Int32=3,  19.Int32=3,  20.Int32=3, 
    //21.Int32=3,  22.Int32=3,  23.Int32=0,  24.Int32=0,  25.Int32=4,  26.Int32=4,  27.Int32=4,  28.Int32=4,  29.Int32=4,  30.Int32=4, 
    //31.Int32=4,  32.Int32=4,  33.Int32=4,  34.Int32=4,  35.Int32=4,  36.Int32=1,  37.Int32=3,  38.Int32=3,  39.Int32=0,  40.Double=0, 
    //41.Int32=1,  42.Int32=0,  43.Double=0,  44.Double=0,  45.Double=0,  46.Int32=0,  47.Int32=0,  48.Int32=1,  49.Double=0,  50.Int32=1,  51.Int32=1,  52.Double=0,  53.Double=0,  54.Double=0,  55.Int32=0,  56.Int32=0,  57.Int32=1
    
    
    public $params=array(
        '0'=>array('name'=>'ControlMode', 'label'=>'Режим управлениЯ',  'limits'=>array('min'=>0, 'max'=>1), 'val_list'=>array(0=>'local', 1=>'remote')),
        '1'=>array('name'=>'Mode', 'label'=>'Режим резервирования', 'limits'=>array('min'=>1, 'max'=>2), 'val_list'=>array(1=>'manual', 2=>'auto')),
        '2'=>array('name'=>'Status', 'label'=>'Позиция переключателя резервирования',
            'val_list'=>array(1=>'GeneralUnit', 2=>'ReserveUnit'), 'limits'=>array('min'=>1, 'max'=>2), 
            /// <summary>
            /// Основным является A1
            /// </summary>
            //GeneralUnit,
            /// <summary>
            /// Основным является A2
            /// </summary>
            //ReserveUnit,
            /// <summary>
            /// Не определен
            /// </summary>
            //None

            ),


        
    );
    
    
    public function updateSingleCommand($command_details){
         //print_r($this->current_values);
         //echo "\n\r";
        
        if($command_details['CommandNum']==36){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                //if($params[0]==2) $this->current_values['Mode']['valueParameter'] = 1;
                //if($params[0]==1) $this->current_values['Mode']['valueParameter'] = 0;
                $this->current_values['Mode']['valueParameter'] =$params[0];
            }
    
        }
        
        if($command_details['CommandNum']==40){
            $params = explode(';', $command_details['Params']);
            if(is_array($params)==true){
                //$output = 'Output'.$params[1];
                //if($params[0]==2) $this->current_values['Status']['valueParameter'] = 1;
                //if($params[0]==1) $this->current_values['Status']['valueParameter'] = 0;
                $this->current_values['Status']['valueParameter'] =$params[0];
            }
        }
    }
    
}
?>
