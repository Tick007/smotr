<?php
echo 'ewrwer';
$html = '<?xml version="1.0" encoding="UTF-8" ?>
<statusreq>
  <auth login="sp" pass="Ujyb2Vjq!"></auth>';
//$html .= '<orderno>3148</orderno>';
$html .= '<changes>ONLY_LAST</changes>';
$html .= '</statusreq>';

//checkOrder;87.10;643;1001;13;55;8123294469;s<kY23653f,{9fcnshwq	1B35ABE38AA54F2931B0C58646FD1321
//action;orderSumAmount;orderSumCurrencyPaycash;orderSumBankPaycash;shopId;invoiceId;customerNumber;shopPassword

$str =	'paymentAviso;3499;643;3499;45159;2000000533182;5742;sodastore.ru-2014';
//$str =	'checkOrder;3499;643;3499;45159;2000000533182;5742;sodastore.ru-2014';


$md5 = strtoupper(md5($str));
echo $md5 ; 

if( $curl = curl_init() ) {
	curl_setopt($curl, CURLOPT_URL, 'http://hamakids.ru/payment/YandexMoney/callback.php'); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_POST, true);
	//curl_setopt($curl, CURLOPT_POSTFIELDS, $html);
 //curl_setopt($curl, CURLOPT_POSTFIELDS, "customerNumber=5742&orderSumAmount=3499&orderSumCurrencyPaycash=643&orderSumBankPaycash=3499&invoiceId=2000000533182&md5=AB6ADAF81AE3D130A20D482774CD63FF&action=checkOrder");
	curl_setopt($curl, CURLOPT_POSTFIELDS, "customerNumber=5742&orderSumAmount=3499&orderSumCurrencyPaycash=643&orderSumBankPaycash=3499&invoiceId=2000000533182&md5=5979432F9DAD5D0539D57FCB51BFA8EF&action=paymentAviso");
	$result = curl_exec($curl);
	curl_close($curl);
}


//$xml = new SimpleXMLElement($result);
echo '<pre>';
print_r($result);
echo '</pre>';
?>