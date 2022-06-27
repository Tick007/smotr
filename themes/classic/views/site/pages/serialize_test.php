

<?php 
$pattern = "0?1:2";
$val1 =  0;
$val2 =324234;


eval('$res1=($val1==$pattern);');
eval('$res2=('."{$val2}=={$pattern}".');'); ///////////////////Правильный
echo '$res1 = '.$res1.'<br>';
echo '$res2 = '.$res2.'<br>';
echo '<br>';
echo $res3=($val2==0?1:2);
?>
<br>
<?php 

$conf = array(
   ///////////////Все перенес в генерацию непосредственно от самих объектов устройств
/*
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
    
    'DownConverter'=>array(
        'ImpedanceValue'=>array('unit'=>'ohm'),
        'InputFrequency'=>array('unit'=>'Hz'),
        'AttenuationValue'=>array('unit'=>'db'),
        'OutputFrequency'=>array('unit'=>'MHz'),
        'ControlMode'=>array('value_list'=>array(0=>'local', 1=>'remote')),
       
    ),
    
    'UpConverter'=>array(
        'ImpedanceValue'=>array('unit'=>'ohm'),
        'InputFrequency'=>array('unit'=>'MHz'),
        'AttenuationValue'=>array('unit'=>'db'),
        'OutputFrequency'=>array('unit'=>'Hz'),
        'ControlMode'=>array('value_list'=>array(0=>'local', 1=>'remote')),
    ),
    */
    /*
    'Amplifier'=>array(
        'power_rf'=>array('unit'=>'Wt'),
        'voltage_beam'=>array('unit'=>'V'),
        'current_beam'=>array('unit'=>'A'),
        'current_body'=>array('unit'=>'A'),
        'voltage_heater'=>array('unit'=>'V'),
        'current_heater'=>array('unit'=>'A'),
        'power_reflectedrf'=>array('unit'=>'Wt'),
        'cabinet_temperature'=>array('unit'=>'C'),
        'inlet_temperature'=>array('unit'=>'C'),
        'outlet_temperature'=>array('unit'=>'C'),
        'differential_temperature'=>array('unit'=>'C'),
        'nameplatevoltage_beam'=>array('unit'=>'V'),
        'attenuatorpower'=>array('unit'=>'Wt'),
        'alcpower'=>array('unit'=>'Wt'),
        'manualpower'=>array('unit'=>'Wt'),
    ),
    
    'Ttranslator'=>array(
        'ImpedanceValue'=>array('unit'=>'ohm'),
        'InputFrequency'=>array('unit'=>'MHz'),
        'AttenuationValue'=>array('unit'=>'db'),
        'OutputFrequency'=>array('unit'=>'Hz'),
        'ControlMode'=>array('value_list'=>array(0=>'local', 1=>'remote')),
    ),
    */
);


echo "<pre>";
print_r($conf);
echo "</pre>";

echo serialize($conf)."<br>";
print_r($conf);

echo "<br><pre>";
echo json_encode($conf, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
echo "</pre>";


?>