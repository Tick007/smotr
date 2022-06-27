<?php
/* @var $this PayloadController */
/* @var $data Payload */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('station_id')); ?>:</b>
	<?php echo CHtml::encode($data->station_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ka_id')); ?>:</b>
	<?php echo CHtml::encode($data->ka_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('received')); ?>:</b>
	<?php echo CHtml::encode($data->received); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('transfered')); ?>:</b>
	<?php echo CHtml::encode($data->transfered); ?>
	<br />


</div>