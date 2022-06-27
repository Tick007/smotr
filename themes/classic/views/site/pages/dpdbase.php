<?php
/*
$row = 1;
if (($handle = fopen($_SERVER['DOCUMENT_ROOT']."/GeographyDPD_20170626.csv", "r")) !== FALSE) {
	while ($data = fgets($handle)) {
		//$stroka = str_getcsv ( $data ,  ";");
		$stroka = str_getcsv ($data, ";" , "\n\r" );
		if($stroka!=NULL){
			print_r($stroka);
			echo '<br><br>';
		}
	}
	fclose($handle);
}
*/

foreach(file($_SERVER['DOCUMENT_ROOT']."/GeographyDPD_20170626_v2.csv") as $line) {
	//echo $line. "\n";
	//echo '<br>';
	//echo '<br>';
	$stroka = str_getcsv ($line, ";" , "\n\r" );
	if($stroka[5]=='Россия') {
		//echo $stroka[0].'<br>';
		print_r($stroka);
		echo'<br>';
		echo $stroka[0].' '.$stroka[2].' '.$stroka[3];
		$region = substr($stroka[1], 2,2);
		echo ' reg='.$region;
		echo'<br><br>';
		
		$rec = new CacheDpdCities();
		$rec->cityId = $stroka[0];
		$rec->regionCode = $region;
		$rec->cityName = $stroka[3];
		$rec->save();
	}
}


?>