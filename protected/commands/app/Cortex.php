<?php
namespace MyApp;
include ('d:\web\wwwroot\arm\protected\models\CortexTables.php'); // /////////Таблицы кортекса
use TCU;
use TMS;
use RAU;
use IFM;
use IFR;
use TMU;

use PDO;
use PDOException;




class Cortex extends BaseDevice
{
    var $jslabel="Cortex";
    
    public $params=array(

        //'0'=>array('name'=>'ImpedanceValue', 'label'=>'Сопротивление', 'val_list'=>array(1=>'50', 0=>'75'), 'unit'=>'ohm'),
        
        
    );
    
    public function __construct($pdo_conn, $id, $show_error=false)
    {
        parent::__construct($pdo_conn, $id);
        //echo 'constructor in cortex';
        //exit();
       // $tcu = new TCU();
        
        $TCU = new TCU();
        //print_r($TCU->fields);////////////работает
        foreach ($TCU->fields as $fid=>$field) {
            $idn = explode('_', $field[1], 3);
            //print_r($idn);
            //echo "\n\r";
            /*Array
            (
                [0] =>
                [1] => 41
                [2] => Word
            )*/
            if($idn[2]=="Word") $this->params['tcu'][$fid]=array('name'=>$idn[2].'_'.$idn[1], 'label'=>'');
            else $this->params['tcu'][$fid]=array('name'=>$idn[2], 'label'=>'');
        }
        
        $TMS = new TMS();
        foreach ($TMS->fields as $fid=>$field) {
            $idn = explode('_', $field[1], 3);
            if($idn[2]=="Word") $this->params['tms'][$fid]=array('name'=>$idn[2].'_'.$idn[1], 'label'=>'');
            else $this->params['tms'][$fid]=array('name'=>$idn[2], 'label'=>'');
        }
        
        $RAU = new RAU();
        foreach ($RAU->fields as $fid=>$field) {
            $idn = explode('_', $field[1], 3);
            if($idn[2]=="Word") $this->params['rau'][$fid]=array('name'=>$idn[2].'_'.$idn[1], 'label'=>'');
            else $this->params['rau'][$fid]=array('name'=>$idn[2], 'label'=>'');;
        }
        
        $IFM=new IFM();
        foreach ($IFM->fields as $fid=>$field) {
            $idn = explode('_', $field[1], 3);
            if($idn[2]=="Word") $this->params['ifm'][$fid]=array('name'=>$idn[2].'_'.$idn[1], 'label'=>'');
            else $this->params['ifm'][$fid]=array('name'=>$idn[2], 'label'=>'');
        }
        
        $IFR=new IFR();
        foreach ($IFR->fields as $fid=>$field) {
            $idn = explode('_', $field[1], 3);
            if($idn[2]=="Word") $this->params['ifr'][$fid]=array('name'=>$idn[2].'_'.$idn[1], 'label'=>'');
            else $this->params['ifr'][$fid]=array('name'=>$idn[2], 'label'=>'');
        }
        
        $TMU = new TMU();
        foreach ($TMU->fields as $fid=>$field) {
            $idn = explode('_', $field[1], 3);
            if($idn[2]=="Word") $this->params['tmu'][$fid]=array('name'=>$idn[2].'_'.$idn[1], 'label'=>'');
            else $this->params['tmu'][$fid]=array('name'=>$idn[2], 'label'=>'');
        }
        
        
        //print_r($this->params);
    }
    
    
    

    
    
    
    /** Метод для возврата дефолтных значений уникальный для Cortex
     * @param boolean $zero_values //////////// верныть нули, по умолчанию нет false
     * @return unknown|boolean[]|number[]|unknown[]
     */
    public function getDefaultValues($zero_values=false){
        
        
        foreach ($this->params as $block_name=>$block) {
        
            foreach ($block as $param_id=>$param) {
            
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
                
                $current_values[$block_name][$np]=array(
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

    
}
?>
