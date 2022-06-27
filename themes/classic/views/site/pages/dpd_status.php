<?php

$request = array();
$request['request']['auth'] = array( 'clientNumber' => '1001030212', 'clientKey' => '76CE6368D2D0E2DA4D5E461AF2397DA459AFF2E0' );


$request['request']['clientOrderNr']=14116;

try{
	echo 'SOAP_CLIENT<br>';

	$SOAP_CLIENT = new SoapClient('http://wstest.dpd.ru:80/services/tracing?wsdl');

	$res = $SOAP_CLIENT->getStatesByClientOrder($request);

	print_r($res);
	echo '<pre>';
	print_r($request);
	echo '</pre>';
}
catch (Exception $e){
	echo '<pre>';
	print_r($e);
	echo '</pre>';
}
?>