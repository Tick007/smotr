<?php
set_time_limit ( 120);


function getContent($url, $referer = null, $proxies = array(null))
{
	$proxies = (array) $proxies;
	$steps = count($proxies);
	$step = 0;
	$try = true;
	while($try){
		// create curl resource
		$ch = curl_init();
		echo $proxies[$step].'<br>';
		$proxy = isset($proxies[$step]) ? $proxies[$step] : null;

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$output = curl_exec($ch); // get content
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // �������� HTTP-���
		echo '$output = '.$output;
		// close curl resource to free up system resources
		curl_close($ch);

		$step++;
		$try = (($step < $steps) && ($http_code != 200));
	}
	if(isset($proxies[$step])) echo 'Подключено через: '.$proxies[$step].', время: '.date('d.m.Y H:i:s');
	else {
		echo '<br>Не удалось подключиться ни через один прокси в списке: <br>
				$http_code = '.$http_code.'<br>';
		print_r($ch);
		echo '<br>'.$proxies[$step-1].'<br>';
	}
	return $output;
}

echo '<br>';

//print_r(getContent('http://foxtools.ru/Proxy'));
echo '<br>';


//print_r(getContent('http://www.ya.ru', null, array(
print_r(getContent('http://slot-cars.ru', null, array(
//print_r(getContent('http://lenta.ru', null, array(
//print_r(getContent('https://vk.com/widget_auth.php?app=5114001&width=200px&_ver=1&url=http%3A%2F%2Fsmotr%2Fsite%2Fpage%3Fview%3Dvktest_serg&referrer=&title=&15089ecf0a3', null, array(
/*
'151.80.225.105:8080',// good
'65.255.32.15:8080', //good
'176.31.175.43:80', //good
'122.226.21.196:8080',//good
'208.76.196.84:80', //good
'128.199.147.170:8888',	//good	
'212.47.235.33:3129',//good
'125.140.118.12:3128', //good
'192.99.3.129:3128',//good
'5.129.231.10:3130', //good
'95.0.218.10:8080',//good
'217.9.195.227:3128',//good
'2.222.45.88:8888',//good
'103.10.22.242:3128',//good
'202.29.97.2:3128',//good
'111.161.126.101:80',//good
'52.20.229.27:3128',//good
'104.28.23.190:80',//good
'141.101.127.102:80',//good
*/		
'185.63.101.87:1080',
'83.239.29.236:8080',
'178.75.72.186:8080',
		
'212.12.69.43:8080',
		/*
'177.234.0.110:3130',
'212.47.233.46:3129',
'178.57.217.150:11897',
'5.189.184.3:3128',
'80.95.113.66:3128',
'199.27.135.76:80',
'80.95.113.70:3128',
'162.208.49.45:3127',
'5.129.231.10:3128',
'103.251.43.53:80',
'118.143.207.1:8080',
'46.105.39.156:3128',
*/


		
		
//'41.86.149.210:80',// bad
//'2.135.238.108:9090',// bad
//'211.103.124.74:80',// bad
//'128.199.103.45:3128',//bad

	/*	
'120.198.237.5:80',//good
'194.84.143.217:8080',//good
'58.180.45.119:3128',//good
'122.226.21.196:9090',//good
'122.226.21.196:80',//good
'190.77.218.168:3128',//good
'201.217.213.170:8080',//good
'23.25.28.59:3130',//good
'91.217.42.3:8080',//good
'218.201.183.18:8080',//good
*/




		
		//'27.251.244.37:8080',//?
		//'194.181.153.206:9090',//?
/*		
		'119.28.14.97:80',
'222.124.149.178:3128',
'218.9.69.2:8080',
'122.96.59.105:82',
'211.170.156.163:80',
'219.93.183.93:80',
'61.154.14.237:8086',
'114.112.91.97:90',
'116.112.66.102:808',
'188.65.234.18:1080',
'213.177.105.14:1080',
'31.28.6.13:8118',
'213.160.143.150:3128',
'177.23.90.1:8181',
'213.111.123.68:8080',
'200.93.201.221:8081',
'178.32.248.125:3128',
'222.42.146.106:8080',
'80.253.28.174:8080',
'218.207.212.79:80',
'198.108.245.243:8080',

'82.139.70.104:80',
'91.225.218.2:3128',
'72.162.36.14:80',
//'222.39.96.152:8118',//?
'183.97.174.70:80',
'115.159.5.247:80',
'59.124.7.128:80',
		
		
		
		
		
'119.252.170.140:8080', //good
'176.241.83.145:8080', //good
'101.4.136.34:9999', //good
'122.146.195.232:3128',//good
'146.148.59.225:8888',//good
'197.210.252.44:80',//good
*/
//'179.43.122.224:3128',
//'128.199.226.223:3128',
//'212.68.51.58:80', //good
//'27.34.251.164:8080',//good
//'195.154.104.88:3128',
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