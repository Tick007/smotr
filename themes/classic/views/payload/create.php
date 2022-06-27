<?php
/* @var $this PayloadController */
/* @var $model Payload */

$this->breadcrumbs=array(
	'Payloads'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Payload', 'url'=>array('index')),
	array('label'=>'Manage Payload', 'url'=>array('admin')),
);
?>

<h1>Create Payload</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>