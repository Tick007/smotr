<?php
/* @var $this PayloadController */
/* @var $model Payload */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'payload-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'station_id'); ?>
		<?php echo $form->textField($model,'station_id'); ?>
		<?php echo $form->error($model,'station_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ka_id'); ?>
		<?php echo $form->textField($model,'ka_id'); ?>
		<?php echo $form->error($model,'ka_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'received'); ?>
		<?php echo $form->textField($model,'received'); ?>
		<?php echo $form->error($model,'received'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transfered'); ?>
		<?php echo $form->textField($model,'transfered'); ?>
		<?php echo $form->error($model,'transfered'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->