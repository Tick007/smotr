<?php
/* @var $this PayloadController */
/* @var $model Payload */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'station_id'); ?>
		<?php echo $form->textField($model,'station_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ka_id'); ?>
		<?php echo $form->textField($model,'ka_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'received'); ?>
		<?php echo $form->textField($model,'received'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'transfered'); ?>
		<?php echo $form->textField($model,'transfered'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->