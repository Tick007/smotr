<DB>
<?php 
for($i=0, $c=count($models); $i<$c; $i++){
	
	?>
	<user>
		<playerId><?php 
		echo $models[$i]['id'];
		?></playerId>
		<alias><?php 
		echo $models[$i]['login'];
		?></alias>
		<displayName><?php 
		echo $models[$i]['first_name'].' '.$models[$i]['second_name'];
		?></displayName>
		<isFriend>true</isFriend>
	</user>
	<?php 
}
?>
</DB>