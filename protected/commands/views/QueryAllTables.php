<DB>
<?php 
for($i=0, $c=count($models); $i<$c; $i++){
	?>
	<TABLE>
		<NAME><?php 
		foreach ($models[$i] as $dbname=>$tablename) echo $tablename;
		?></NAME>
		<SOME>qqq</SOME>
	</TABLE>
	<?php 
}
?>
</DB>