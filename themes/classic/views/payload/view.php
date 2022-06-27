<?php
/* @var $this PayloadController */
/* @var $model Payload */

$this->breadcrumbs=array(
	'Payloads'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Payload', 'url'=>array('index')),
	array('label'=>'Create Payload', 'url'=>array('create')),
	array('label'=>'Update Payload', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Payload', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Payload', 'url'=>array('admin')),
);
?>

<h1>View Payload #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'station_id',
		'ka_id',
		'received',
		'transfered',
	),
)); ?>
