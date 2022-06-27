<?php 


$connection = Yii::app()->db;//get connection
$dbSchema = $connection->schema;
//or $connection->getSchema();
$tables = $dbSchema->getTables();//returns array of tbl schema's
foreach($tables as $tbl)
{
	echo $tbl->rawName, ':<br/>', implode(', ', $tbl->columnNames), '<br/>';
}