<?php 
$merchant =  PaymentMerchants::model()->findByPk(1);


//$OutSum = Yii::app()->getRequest()->getParam('OutSum', NULL);
//$InvId = Yii::app()->getRequest()->getParam('InvId', NULL);
//$SignatureValue = Yii::app()->getRequest()->getParam('SignatureValue', NULL);

$InvId = 50;
$OutSum = 12930;
$pass = $merchant->result_pass;
$login = $merchant->login;

$crc = calculate_crc($InvId,$OutSum,$pass,$login);

$url = $merchant->shopurl.'/'.$merchant->action_result;

$url.='?OutSum='.$OutSum.'&InvId='.$InvId.'&SignatureValue='.$crc;

echo $url.'<br>';
//exit();


$referer = $_SERVER['SERVER_NAME'];

$ch = curl_init();
//echo $proxies[$step].'<br>';
//$proxy = isset($proxies[$step]) ? $proxies[$step] : null;

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_REFERER, $referer);
curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

$output = curl_exec($ch); // get content
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // �������� HTTP-���
echo '$output = '.$output;
// close curl resource to free up system resources
curl_close($ch);



function calculate_crc($order_id, $sum, $pass, $login){
    $crc = null;
    //$crc_string = '';
    $crc_string = $sum.':';
    $crc_string.= $order_id.':';
    $crc_string.= $pass.':Shp_item=1';
    //echo $crc_string.'<br>';
    $crc = md5($crc_string);
    return $crc;
}

?>