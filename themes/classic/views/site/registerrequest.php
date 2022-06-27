
<?php if(Yii::app()->user->hasFlash('register_request')){ ?>
<div class="confirmation">
<?php $infomes =Yii::app()->user->getFlash('register_request'); 
echo $infomes;

?>
</div>
<?php 
}
?>




<?php echo CHtml::beginForm(array(
        'action' => 'site/requestregister',
        'method' => 'post')); ?>
<?php if (isset($_POST['RegisterrequestForm']['callback'])==false) echo CHtml::errorSummary($contact); ?>

<div class="requestregform">

		<?php
			$labels = $contact->attributeLabels();
			foreach($labels as $var_name=>$label_name){
				if(!in_array($var_name, $contact->notListHtmlFields())){
				?>
			<div class="vacblock">
  		      <?php echo CHtml::activeLabel($contact,$var_name, array('required'=>true)); ?>
	          <?php echo CHtml::activeTextField($contact,$var_name,array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp', 'placeholder'=>$contact->getAttributeLabel($var_name))); ?>
	        </div>
				<?php 
				}
			}////////endforeach
		?>

		

      	 <div ><?php
       		echo  CHtml::submitButton('werwe', array('class'=>'submit_register_request', 'value'=>'Отправить заявку'));	
        ?></div> 
		

</div>
        
<?php echo CHtml::endForm(); ?>




<style>
.vacblock label{
display:block;
}

.vacblock{
margin-bottom:20px;
}

.vacblock label span.required {
color: #F00;
margin-left: -2px;
}

.submit_register_request{
	width:156px;
	height:28px;
	background-color:#97c93d;
	border-radius:5px;
	border:none;
	color:#FFF;
}


.confirmation{
	font-size:16px;
	margin-top:20px;
	margin-bottom:30px;
	color:#408080;
	font-weight:bold;
	text-decoration:underline;

}


div.errorSummary
{
	border: 2px solid #C00;
	padding: 7px 7px 12px 7px;
	margin: 0 0 20px 0;
	background: #FEE;
	font-size: 0.9em;
}

div.errorSummary p
{
	margin: 0;
	padding: 5px;
	font-size:13px;
	font-weight:bold;
}

div.errorSummary ul
{
	margin: 0;
	padding: 0 0 0 20px;
}

div.errorSummary ul li
{
	list-style: square;
}

</style>