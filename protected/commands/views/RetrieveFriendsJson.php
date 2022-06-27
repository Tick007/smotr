<?php 
$phparr =  array();
for($i=0, $c=count($models); $i<$c; $i++){
	$linearr = NULL;
	$linearr['playerId']  = $models[$i]['id'];
	$linearr['alias']  = $models[$i]['login'];
	$linearr['displayName']= $models[$i]['first_name'].' '.$models[$i]['second_name'];
	$linearr['isFriend'] = "true";

	$phparr[]=$linearr;
}

echo json_encode($phparr );
?>
