<?php

class Command extends CConsoleCommand
{
	
	private $lvsnumsrc;
	private $lvsnumdst;
	private $dstab;
	private $conrollerport;
	private $cortexport;
	private $cortexip;
	
	
	
	public function init(){
			ini_set( 'date.timezone', 'Europe/Moscow' );
			ini_set("memory_limit","768M");
			Yii::import('FHtml');
	//		include('E:\web\wwwroot\arm\protected\components\WebSocketServer.php');
	//		include('E:\web\wwwroot\arm\protected\components\ContProtocol.php');

	}
	
	
	
	
		
		
			
			

	
	/*
		public function showBytes($pack, $name, $field_val){
			echo '<div><div style="float:left; width:150px">'.$name.'<br>'.$field_val.'</div><div style="float:left">';
			for($i=0; $i<strlen($pack); $i++){
				echo $i.': '.ord($pack[$i]).'<br>';
			}
			echo '</div>';
			echo '</div><br style="clear:both">';
		}
		*/
		
		
		
		
}