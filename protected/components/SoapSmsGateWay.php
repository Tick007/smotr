<?php
class SoapSmsGateWay
{

	function __construct() {
		//print "In SoapSmsGateWay constructor\n";
	}
	
	
	public function sendSms($req){
		$model = new Wdsl();
		ob_start();
		print_r($req);
		var_dump($req);
		$model->req = ob_get_clean();
		$model->save(false);
	}
	
}
?>