<?php
///////////////Эта версия подверглась модернизации в 2021 года для эмулятора АРМ в рамках СМОТР

class ContProtocol
{
	
	private $lvsnumsrc;
	private $lvsnumdst;
	private $dstab;
	private $srcab;
	
	public $hdr= array(
		'Version'=>array('4', 'int', '2011'),
		'Size' =>array('4', 'int', '112'), // Размер всего сообщения 
        // = sizeof(TmsgHeader) + ParCount * sizeof(S_Par) + MemoSize + ErrSize
       'KA_Num'=>array('4', 'int', '5'), // Номер космического аппарата. Для Ямал-401 = 6. 
       'SrcLvs'=>array('4', 'int', '20'), // Номер ЛВС отправителя сообщения
       'SrcAb'=>array('4', 'int',  '1'), // Номер абонента отправителя
       'DstLvs'=>array('4', 'int', '20'), // Номер ЛВС адресата
       'DstAb'=>array('4', 'int', '3'), // Номер абонента адресата
       'T_create_ticks'=>array('8', 'long', '0'),      // Время формирования сообщения.
       'T_com_for_kvit'=>array('8', 'long', '0'),      // Имеет смысл для квитанции. Содержит копию времени 
       'Tag'=>array('8', 'double', '0'),                      // Резерв.
       'MsgType'=>array('4', 'int', '1'), // Тип сообщения 4 байта (cм. Enum E_MsgType)
       'UnitType'=>array('4', 'int', '21'),                  // Тип устройства.
       'UnitMark'=>array('4', 'int', '21'),                  // Марка устройства.
       'UnitNum'=>array('4', 'int', '0'),                   // Номер устройства.
       'Timeout_ms'=>array('4', 'int', '10000'),             // Время, отведенное на выполнение команды [мс].
       'TWork_ms'=>array('4', 'int', '10000'),               // Для команды = 0.
              // Для квитанции = время, затраченное на выполнение команды[мс].

        'CommandNum'=>array('4', 'int', '105'),  // Номер команды, сообщения Для квитанции это Номер отработанной команды
        'KvitNum'=>array('4', 'int', '0'), // Номер квитанции
        'KvitResult'=>array('4', 'int', '0'),// Код возврата (результат выполнения команды). В команде не имеет значения (задается 0). В квитанции 0 – норма, остальные числа определяются в рабочем порядке.
        'IdProc'=>array('4', 'int', '2000'),                   // Резерв.
        
        'ScriptNum'=>array('4', 'int', '0'),              // Резерв.
		'ParCount'=>array('4', 'int', '0'), // Количество параметров
        
        'MemoSize'=>array('4', 'int', '0'), // Размер неструктурированных данных
        // ParCount и MemoSize могут задаваться одновременно.
        //  При этом в теле сообщения  за параметрами непосредственно следует memo-поле
        'ErrSize'=>array('4', 'int', '0'), // Размер Error-поля (символьные данные UTF8 с описанием ошибки, оканч.0)
	);
	
	public $pars;
	
	public $byteHeader;
	
	public $bytePars;
	
	//public $paramsstr;
		
	public function __construct($lvsnumsrc=NULL , $lvsnumdst=NULL, $dstab=NULL, $srcab=NULL)
	{
		//echo '324234423';
		//exit();
		if(isset(Yii::app()->session) AND ($lvsnumsrc==NULL OR $lvsnumdst==NULL OR $dstab==NULL OR $srcab==NULL)){
				$this->hdr['SrcLvs'][2]=  Yii::app()->session['lvsnumsrc'];
				$this->hdr['DstLvs'][2]=  Yii::app()->session['lvsnumdst'];
				$this->hdr['DstAb'][2]=   Yii::app()->session['dstab'];
				$this->hdr['SrcAb'][2]=   Yii::app()->session['srcab'];
		}
		elseif(isset($lvsnumsrc) AND isset($lvsnumdst) AND isset($dstab) AND isset($srcab)) {/////////////Вариант установки из консоли
				$this->hdr['SrcLvs'][2]=  $lvsnumsrc;
				$this->hdr['DstLvs'][2]=  $lvsnumdst ;
				$this->hdr['DstAb'][2]=  $dstab;
				$this->hdr['SrcAb'][2]=  $srcab;
		}
	}
	/*
	public function setConsoleParams($lvsnumsrc , $lvsnumdst, $dstab){ ////////////////Сделанна что бы можно было устанавливать эти параметры из консоли
		$this->lvsnumsrc = $lvsnumsrc ;
		$this->lvsnumdst =  $lvsnumdst;
		$this->dstab = $dstab;
	}////////////////public function setConsoleParams($lvsnumsrc , $lvsnumdst, $dstab){
		*/
	
	
	
	/**Метод для подготовки команды на контроллер на изменение состояния входов матрицы
	 * @param номер устройства $num
	 * @param з
	 */
	public function matrixSetOutput($num, $output, $value){
	    //Замкнуть цепь.
	   // CLOSE_PATH = 20,
	    //Разомкнуть цепь.
	   // OPEN_PATH = 21,
	    
	    $time = microtime();
	    $time_parts = explode(" ", $time);
	    $this->hdr['T_com_for_kvit'][2] = mktime(22,20,0,9,8, 1974);
	    $this->hdr['T_create_ticks'][2] =mktime(22,20,0,9,8, 1974);
	    if($value==0) $this->hdr['CommandNum'][2] =21;
	    elseif($value==-1) $this->hdr['CommandNum'][2] =22;/////Разомкнуть все цепи
	    else $this->hdr['CommandNum'][2] =20;
	    $this->hdr['UnitType'][2] =130;
	    $this->hdr['UnitMark'][2] =90;
	    $this->hdr['UnitNum'][2] =$num;
	    
	    
	    

	   if($value==0){
	       $this->pars[0]['type']=array(4, 'int', 3);
	       $this->pars[0]['value']=array(8, 'double', $output);
	    }
	    elseif($value>0) {
	        
	        $this->pars[0]['type']=array(4, 'int', 3);
	        $this->pars[0]['value']=array(8, 'double', $value);
	        
	       $this->pars[1]['type']=array(4, 'int', 3);
	       $this->pars[1]['value']=array(8, 'double', $output);
	    }
	    
	    $this->getHdrByte();
	    $this->getParsByte();
	    $this->finalizeMassage();
	    
	}
	
	
	/** Метоод для подготовки команды на контроллер на изменение входной частоты конвертера DnConverter Miteq U-9901-1-1K
	 * @param номер устройства $num
	 * @param частота $freq
	 */
	public function dnSetInpFreqPrepare($num, $freq ){
	    $time = microtime();
	    $time_parts = explode(" ", $time);
	    $this->hdr['T_com_for_kvit'][2] = mktime(22,20,0,9,8, 1974);
	    $this->hdr['T_create_ticks'][2] =mktime(22,20,0,9,8, 1974);
	    $this->hdr['CommandNum'][2] =32;
	    $this->hdr['UnitType'][2] =61;
	    $this->hdr['UnitMark'][2] =101;
	    $this->hdr['UnitNum'][2] =$num;
	    
	    ////////////Пара для частоты
	    $this->pars[0]['type']=array(4, 'int', 2);
	    $this->pars[0]['value']=array(8, 'double', $freq);
	    
	    $this->getHdrByte();
	    $this->getParsByte();
	    $this->finalizeMassage();
	}
	
	/** Метоод для подготовки команды на контроллер на изменение выходной частоты конвертера DnConverter Miteq U-9901-1-1K
	 * @param номер устройства $num
	 * @param частота $freq
	 */
	public function dnSetOutFreqPrepare($num, $freq ){
	    $time = microtime();
	    $time_parts = explode(" ", $time);
	    $this->hdr['T_com_for_kvit'][2] = mktime(22,20,0,9,8, 1974);
	    $this->hdr['T_create_ticks'][2] =mktime(22,20,0,9,8, 1974);
	    $this->hdr['CommandNum'][2] =34;
	    $this->hdr['UnitType'][2] =61;
	    $this->hdr['UnitMark'][2] =101;
	    $this->hdr['UnitNum'][2] =$num;
	    
	    ////////////Пара для частоты
	    //0.NotSpecify=???
	    //1.Int64
	    //2.Double
	    //3.Int32
	    //4.DateTime
	    //5. Bool
	    //6.      //////////////Ошибка в контроллере
	    //7. String
	    //8. NotSpecify
	    $this->pars[0]['type']=array(4, 'int', 3);
	    $this->pars[0]['value']=array(8, 'double', $freq);
	    
	    $this->getHdrByte();
	    $this->getParsByte();
	    $this->finalizeMassage();
	}
	
	public function dnAttenPrepare($num, $attenval ){
	    $time = microtime();
	    $time_parts = explode(" ", $time);
	    $this->hdr['T_com_for_kvit'][2] = mktime(22,20,0,9,8, 1974);
	    $this->hdr['T_create_ticks'][2] =mktime(22,20,0,9,8, 1974);
	    $this->hdr['CommandNum'][2] =21;
	    $this->hdr['UnitType'][2] =61;
	    $this->hdr['UnitMark'][2] =101;
	    $this->hdr['UnitNum'][2] =$num;
	    
	    ////////////Пара для частоты
	    //0.NotSpecify=???
	    //1.Int64
	    //2.Double
	    //3.Int32
	    //4.DateTime
	    //5. Bool
	    //6.      //////////////Ошибка в контроллере
	    //7. String
	    //8. NotSpecify
	    $this->pars[0]['type']=array(4, 'int', 2);
	    $this->pars[0]['value']=array(8, 'double', $attenval);
	    
	    $this->getHdrByte();
	    $this->getParsByte();
	    $this->finalizeMassage();
	}
	
	
	
	
	

	
	public function Controller105Prepare(){
			$time = microtime();
			$time_parts = explode(" ", $time);
			$this->hdr['T_com_for_kvit'][2] = mktime(22,20,0,9,8, 1974);
			$this->hdr['T_create_ticks'][2] =mktime(22,20,0,9,8, 1974);
			$this->hdr['CommandNum'][2] =105;
			$this->hdr['UnitType'][2] =21;
			$this->hdr['UnitMark'][2] =21;
			$this->hdr['UnitNum'][2] =0;
			$this->getHdrByte();
			$this->finalizeMassage();
	}////////ublic function Controller105Prepare(){
	
	
	public function RequestDeviceState($pop_conn, $UnitNum, $UnitType, $UnitMark){
			$cp = new ContProtocol();
			$cp->hdr['CommandNum'][2] =100;
			$cp->hdr['UnitType'][2] =$UnitType;
			$cp->hdr['UnitMark'][2] =$UnitMark;
			$cp->hdr['UnitNum'][2] =$UnitNum;
			$cp->getHdrByte();
			$cp->finalizeMassage();
			socket_write($pop_conn,  $cp->byteHeader );
			
	}
	
	
	
	 public  function readMessage($message){
		/*for($i=0; $i<strlen($message); $i++)
		{
			echo $message[$i].'<br>';
		}
		*/
		$position = 0;
		foreach($this->hdr as $name=>$params){
			$field_lenth = $params[0];
			//if(mb_strlen($message)<=$position+$field_lenth){
    			$binstr = substr($message, $position,$field_lenth);
    			$field = @unpack("i", $binstr);
    			//var_dump($field);
    			if(gettype($field)=="boolean" ) {
    			    break;
    			    echo 'Ошибка при чтении сообщения'."\n\r";
    			    return FALSE;
    			}
     			$this->hdr[$name][2]=$field[1];
        		$position = $position+$field_lenth;
		}
		
		

		
	if($this->hdr['ParCount'][2]>0) $this->fillPairs($message);
		
	
	return true;
	
	}
	
	
	public function getParsByte(){////////////Покуем параметры в байты
	    //print_r($this->pars);
		for($i=0; $i<count($this->pars); $i++){
			
			
			$pack=$this->packValue($this->pars[$i]['type'][0], $this->pars[$i]['type'][1], $this->pars[$i]['type'][2]);
			$pack.=$this->packValue($this->pars[$i]['value'][0], $this->pars[$i]['value'][1], $this->pars[$i]['value'][2]);
			$this->bytePars.=$pack;
		//	echo '$pack('.$i.') = '.strlen($pack).'|   ';
		//	echo 'getParsByte('.$i.') = '.strlen($this->bytePars).'|   ';
		}////////////for($i=0; $i<count($this->pars); $i++){
			
		//	echo 'getParsByte() = '.strlen($this->bytePars).'|   ';
			
	}/////////////////
	
	public function fillPairs($message)///////////////Читаем побитово пары
	{
		//echo 'werewr';

		
		$pars_num = $this->hdr['ParCount'][2];
		if($pars_num>200){
		    ////////////////////////Явная ошибка, это не собранное сообщение
		    echo 'message wasnt read'."\n\r";
            return false;
		}
		$pairs_len = $pars_num*12;
		$position = 108;
		  for ($i = 0; $i < $pars_num; $i++)
            {
				

			   $par_valbin = substr($message,$position+($i*12),4);
			   $par_znbin  =substr($message,($position+($i*12)+4),8);
			  // echo strlen($message).'; ';
 
			  $par_val =unpack("i", $par_valbin);
			  $par_zn =unpack("d", $par_znbin);
			  //echo "i:".$i."; ";
			  $this->pars[$i]=array($par_val[1] =>$par_zn[1]);
				
				//$this->paramsstr.=$par_zn[1].';';
				
            }
		
	}////////////public function fillPairs()///////////////
	
	public function reverse($income)
	{
		$outcome = '';
		echo '<hr>';
		//echo strlen($income);
		for($i=0; $i<strlen($income); $i++){
		echo $i.': '.ord($income[$i]).'<br>';
	}
		
				

			$qqq[0] = $income[3];
			$qqq[1] = $income[2];
			$qqq[2] = $income[0];
			$qqq[3] = $income[1];
			
			echo '<hr>';
			return $income;
			
	}
	
	public function finalizeMassage()
	{
		//echo '1<br>';
		$end = (-1)*$this->hdr['Version'][2];
		if(is_array($this->pars)&&count($this->pars)>0) {
		    //echo '2: '.count($this->pars).'<br>';
			$this->hdr['Size'][2] = $this->hdr['Size'][2] +(count($this->pars)*12);
			
			$this->hdr['ParCount'][2] = count($this->pars);
			
			$this->byteHeader = null;
			$this->getHdrByte();
			
			//echo $this->byteHeader;
			echo mb_strlen($this->byteHeader);
			echo '<br>';
			echo mb_strlen($this->bytePars);
			
			$this->byteHeader.=$this->bytePars;
			
			echo ' strlen = '.strlen($this->byteHeader);
			echo ' count($this->pars) = '.count($this->pars);
			echo ' $this->hdr[Size][2] = '.$this->hdr['Size'][2];
			echo ' $this->bytePars = '.$this->bytePars;
			echo ' len of $this->bytePars = '.strlen($this->bytePars);
			
			
		}
		
		
		
		$pack = pack("i", $end);
		$this->byteHeader.=$pack;
		
	}
	
	public function packValue($field_lenth, $field_type, $field_val)
	{
			//////4294967295 - это все единицы в 32 разрядах
				//echo 'val= "'.$field_val.'"<br>';
				if($field_type=='int') $pack = pack("i", $field_val);
				else {
					//echo $field_val;
					//$binval = decbin($field_val);
					//$field_val = 4294967296;
					/*
					if($field_val>4294967295) {
						
						
						$qqq = $field_val/4294967295;
						echo 'qqq ('.$field_val.'/4294967295) = '.$qqq.'<br>';
						//echo $field_val>>;
					
						$mlbyte = pack("i", 4294967295);
						$st = $field_val-4294967295;
						$stbyte = pack("i", $st);
						$pack = $stbyte.$mlbyte;
					}
					else
					*/
					if($field_type=='double')  $pack =  pack("d", $field_val);
					else  $pack =  pack("i", 0).pack("i", $field_val);
					}
				//var_dump($pack);
				//$qqq = strlen($pack) - 4;
				//$pack_cut = substr($pack,0, $qqq);
				
				return $pack;
	}
	
	
	public function getHdrByte(){
	    //$this->byteHeader = '';
		foreach($this->hdr as $name=>$params){
			$field_lenth = $params[0];
			$field_type = $params[1];
			$field_val = $params[2];
			
			$pack= $this->packValue($field_lenth, $field_type, $field_val);
			$this->byteHeader.=$pack;
			//$this->showBytes($pack, $name, $field_val);
			//echo $pack;
			
		}///////////foreach($this->hdr as $name=>$params){
			
		
			
	}///////////public function getHdrByte(){
	
	public function showBytes($pack, $name, $field_val){
	echo '<div><div style="float:left; width:150px">'.$name.'<br>'.$field_val.'</div><div style="float:left">';
	for($i=0; $i<strlen($pack); $i++){
		echo $i.': '.ord($pack[$i]).'<br>';
	}
	echo '</div>';
	echo '</div><br style="clear:both">';
}
	
}

?>