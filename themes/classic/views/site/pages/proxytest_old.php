<?php



function getContent($url, $referer = null, $proxies = array(null))
{
	$proxies = (array) $proxies;
	$steps = count($proxies);
	$step = 0;
	$try = true;
	while($try){
		// create curl resource
		$ch = curl_init();
		$proxy = isset($proxies[$step]) ? $proxies[$step] : null;

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$output = curl_exec($ch); // get content
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // �������� HTTP-���

		// close curl resource to free up system resources
		curl_close($ch);

		$step++;
		$try = (($step < $steps) && ($http_code != 200));
	}
	if(isset($proxies[$step])) echo 'Подключено через: '.$proxies[$step].', время: '.date('d.m.Y H:i:s');
	else {
		echo '$http_code = '.$http_code.'<br>';
		print_r($ch);
	}
	return $output;
}

echo '<br>';

//print_r(getContent('http://foxtools.ru/Proxy'));
echo '<br>';


print_r(getContent('http://www.ya.ru', null, array(
'159.8.36.242:3128',
'110.77.209.254:8080',
'117.164.4.184:8123',
//'119.252.170.140:8080', //good
//'176.241.83.145:8080', //good
//'101.4.136.34:9999', //good
//'122.146.195.232:3128',//good
//'146.148.59.225:8888',//good
//'197.210.252.44:80',//good
'179.43.122.224:3128',
'128.199.226.223:3128',
//'212.68.51.58:80', //good
//'27.34.251.164:8080',//good
'195.154.104.88:3128',
//'162.246.23.9:3128', //good
//'94.100.57.237:8080',//good
//'107.182.17.243:8089',//good
//'69.197.148.18:8089',//good
//'207.5.112.114:8080',//good
//'50.22.206.179:8080', ///good
//'146.185.187.238:8118',//good
//'157.231.223.236:9001',//good
//'54.147.66.203:3128',//good
//'109.195.17.205:1080',//good
//'195.62.79.238:3128',//good
//'184.107.188.58:8080',//good
//'195.222.125.2:3128', //good
//'195.222.125.2:8080', //good
//'109.99.150.2:8080',//good
//'81.24.82.167:3128',//good
//'143.89.225.246:3128',//good
//'162.220.52.175:7808',//good
//'37.131.208.141:8080',// good
//'173.201.183.172:8000', //good
//'186.90.151.47:9064', //good
//'189.113.64.126:8080',//good
//'41.222.196.52:8080', //good
//'37.228.134.52:7808', //good
//'78.110.144.195:8080', //good
//'37.8.154.165:3128', //good
//'194.135.220.18',
//'201.211.130.124:9064',
//'218.201.42.115:80', //good
//'80.78.76.3:3128', //good
//'189.89.159.122:8402', //good
 //'212.25.61.158:8080',//good
 //'210.6.237.191:3128',//good
 //'64.31.22.131:8089'//good
 )));



?>