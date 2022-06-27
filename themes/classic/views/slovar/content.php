<?php 
$a = rand(0,count($words)-1 );
$words_ajax = json_encode($words[$a]);
?>
<?php if(trim($words_ajax)!='') echo $words_ajax;
//else echo "json = null";?>