<?php
/* @var $this PayloadController */
/* @var $model Payload */

$this->breadcrumbs=array(
	'Payloads'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Payload', 'url'=>array('index')),
	array('label'=>'Create Payload', 'url'=>array('create')),
	array('label'=>'View Payload', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Payload', 'url'=>array('admin')),
);
?>

<h1>Update Payload <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>