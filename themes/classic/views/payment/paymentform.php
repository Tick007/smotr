<?php
/* @var $this PaymentController */
/* @var $model Payment */
/* @var $form CActiveForm */
?>

<div class="payment_summary">
<h1>Информация о платеже</h1>
	<ul>
		<li><span class="">Поставщик:</span><span class=""><?php echo $merchant->shopname?></span></li>
		<li><span class="">Описание поставщика:</span><span class=""><?php echo $merchant->description?></span></li>
		<li><span class="">Номер заказа:</span><span class=""><?php echo $model->order_id?></span></li>
		<li><span class="">Сумма к оплате заказа:</span><span class=""><?php echo $model->sum?></span></li>
		<li><span class="">Валюта платежа:</span><span class="">&nbsp;</span></li>
	</ul>
</div>

<div class="form">

<?php

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'paymentform-form',
    'enableAjaxValidation' => false
));
?>



    <!-- <p class="note">
		Fields with <span class="required">*</span> are required.
	</p>-->

    <?php //echo $form->errorSummary($model); ?>

		<?php echo CHtml::hiddenField('return_url', $return_url); ?>
 
        <?php  echo $form->error($model,'order_id'); ?>

        <?php // echo $form->labelEx($model,'shop_id'); ?>
        <?php echo $form->hiddenField($model,'shop_id'); ?>
        <?php // echo $form->error($model,'shop_id'); ?>
        
 		<?php echo $form->hiddenField($model,'order_id'); ?>
    

        <?php // echo $form->labelEx($model,'shop_id'); ?>
        <?php echo $form->hiddenField($model,'sum'); ?>
        <?php // echo $form->error($model,'sum'); ?>

    

        <?php // echo $form->labelEx($model,'shop_id'); ?>
        <?php echo $form->hiddenField($model,'signature'); ?>
        <?php // echo $form->error($model,'signature'); ?>

    
    <div class="card">
	
	<div class="bank_name">BANK NAME</div>

	<div class="cardchip"></div>

	<div class="row card_number">
		 <?php  echo $form->labelEx($model,'card_number1'); ?>
		<div class="cell">
       
        <?php echo $form->textField($model,'card_number1'); ?>
        <?php //echo $form->error($model,'card_number1'); ?>
    	</div>

		<div class="cell">
        <?php echo $form->textField($model,'card_number2'); ?>
        <?php //echo $form->error($model,'card_number2'); ?>
   		</div>

		<div class="cell">

        <?php echo $form->textField($model,'card_number3'); ?>
        <?php //echo $form->error($model,'card_number3'); ?>
    	</div>

		<div class="cell lastgroup">

        <?php echo $form->textField($model,'card_number4'); ?>
        <?php //echo $form->error($model,'card_number4'); ?>
    	</div>
	</div>
	<br>
 <?php echo $form->labelEx($model,'exp_day'); ?>
	<div class="row card_expr">
		<div class="cell">

        <?php echo $form->textField($model,'exp_day'); ?>
        <?php //echo $form->error($model,'exp_day'); ?>
    </div>
/
		<div class="cell">
        <?php // echo $form->labelEx($model,'exp_mounth'); ?>
        <?php echo $form->textField($model,'exp_mounth'); ?>
        <?php //echo $form->error($model,'exp_mounth'); ?>
    </div>
	</div>
		<div class="cell">
        <?php echo $form->labelEx($model,'code'); ?>
        <?php echo $form->textField($model,'code'); ?>
        <?php //echo $form->error($model,'code'); ?>
    </div>

	<br>
	
	<div class="row">
		<div class="cell">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php //echo $form->error($model,'name'); ?>
    </div>

	<div class="cell">
        <?php echo $form->labelEx($model,'surname'); ?>
        <?php echo $form->textField($model,'surname'); ?>
        <?php //echo $form->error($model,'surname'); ?>
    </div>
    </div>

</div>
<br>
	<div align="center" class="row buttons">
        <?php echo CHtml::submitButton('Оплатить'); ?>
    </div>

<?php $this->endWidget(); ?>

</div>
<!-- form -->