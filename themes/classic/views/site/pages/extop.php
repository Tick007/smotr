<?php
$html = '<?xml version="1.0" encoding="UTF-8" ?>
<statusreq>
  <auth login="soda" pass="Ujyb2Vjq!"></auth>';
//$html .= '<orderno>3148</orderno>';
$html .= '<changes>ONLY_LAST</changes>';
$html .= '</statusreq>';

echo $html;



if( $curl = curl_init() ) {
	curl_setopt($curl, CURLOPT_URL, 'http://extop.ru/orders/api/index.php');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $html);
	$result = curl_exec($curl);
	curl_close($curl);
}




$statusArr = array(
		'NEW' 		=> 0,
		'ACCEPTED' 	=> 1,
		'DELIVERY' 	=> 2,
		'COMPLETE' 	=> 3,
		'CANCELED'	=> 4,
		'PARTIALLY' => 5
);


$xml = new SimpleXMLElement($result);

print_r($result);

?>
