<?php 

//////////////������� ������� ����� � ����� xls
require_once 'excel_reader2.0.php';
//print_r($attributes);
$this->layout="nomenu";

//$data = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT'].'/spanish_words.xls');
$data = new Spreadsheet_Excel_Reader('E:/dropbox/Dropbox/espanol/spanish_words.xls');
$num_of_rows =$data->rowcount(2) ;

//echo $num_of_rows.'<br>';

$data->setOutputEncoding('utf-16LE');
//$data->setUTFEncoder('iconv');
//$data->setUTFEncoder('mb');


for($i=2; $i<=$num_of_rows; $i++) {
	//\\echo $i.': '.$data->val($i, 1).'<br>';
	//echo $this->PRODUCT;
	//echo $data->val($i, 2, 2).': '.$data->val($i, 3, 2).' '.$this->showBytes($data->val($i, 3, 2),'qqq', 1) .'<br>';
	//echo $data->val($i, 2, 2).': '.iconv("UTF-8", "CP1251", $data->val($i, 3, 2)).'<br>';
	$eng = $data->val($i, 2, 2);
	$rus = fixdata($data->val($i, 3, 2));
	$descr = $data->val($i, 4, 2);
	$pod = substr($eng, 0,1);
	
	if(strstr($eng,'>' ) || strstr($eng,'@' ) || strstr($eng,'520' ) || strstr($rus,'=' ) ){
		$eng = fixdata($data->val($i, 2, 2));
		$rus = $data->val($i, 3, 2);
		$pod = substr($eng, 0,1);
		
	}
	
	
	if(trim($rus) && trim($eng) ) {
		$words[] = array('esp'=>$eng,'rus'=>$rus, 'descr'=>$descr,'pod'=>$pod);
	}
	
	
	
	//echo $data->val($i, 2, 2).': '.$data->val($i, 3, 2).'<br>';
}


//print_r($words);


$a = rand(0,count($words)-1 );

//print_r($words[$a]);

$words_ajax = json_encode($words[$a]);
//echo '<br>';
//print_r($words_ajax);

?>

<script>

<?php if(trim($words_ajax)!='') echo "json = $words_ajax";
else echo "json = null";?>;

function qqq(){
	
	
	setTimeout("showPodsk()", 100);
	setTimeout("showEsp()", 600);
	setTimeout("showDescr()", 100);
	setTimeout("location.reload(true)", 3000);
}

function showPodsk(){
	$('#pod').toggle();
}

function showDescr(){
	$('#descr').toggle();
}

function showEsp(){
	$('#esp').toggle(750);
}



$(document).ready(function(){

	if(json!=null) {
		//console.log(json);
		$('#rus').html(json.rus);
		$('#esp').html(json.esp);
		$('#pod').html(json.pod);
		$('#descr').html(json.descr);
		//setTimeout("qqq()", 3000);
		setTimeout("qqq()", 1000);
	}
	else{
		//alert('Пустой json');
		setTimeout("location.reload(true)", 100);
		}
});



</script>
<table width="300" border = "0" style="font-size: 18px">
<tr>
	
	<td align="center" valign="middle" width="130" ><div id="rus" >&nbsp;</div></td>
	<td><div id="pod" style="display: none">&nbsp;</div></td>
	<td align="center" valign="middle" width="130"><div id="esp" style="display: none">&nbsp;</div></td>
	
</tr>
<tr>
	
	<td colspan="3"  align="center" valign="middle" ><div id="descr" style="display: none">&nbsp;</div></td>
	
</tr>
</table>
<?php


function fixdata($string){
	
	$fixed = "";
	$qwerty="";
	$k=0;
	for($i=0;$i<strlen($string);$i=$i+2 ){
		$k=$i/2;
		//$qqq = unpack('s',substr($string.' ', $i, 2));
		
		//$fixed.=$qqq[1].'|';
		$fixed.= iconv('UTF-16LE', 'UTF-8', substr($string.' ', $i, 2));
		//var_dump($qqq);
		/*
		if(abs($k)==$k){
			$qwerty[]
		}
		*/

	}
	

	
	return $fixed;
}

?>