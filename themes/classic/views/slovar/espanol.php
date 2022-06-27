<div class="form">
<?php $form=$this->beginWidget('CActiveForm'); ?>

<?php echo $form->errorSummary($model); ?>

<table width="300" border = "0" >
<tr>
	<td>Всего слов</td>
	<td><?php echo ($num_of_rows)?></td>
	<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
	<td><?php echo $form->labelEx($model,'word_filter'); ?></td>
	<td><?php echo $form->textField($model,'word_filter', $htmlOptions = array('style'=>'width:100px')); ?></td>
	<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
	<td><?php echo $form->labelEx($model,'limit_up'); ?></td>
	<td><?php echo $form->textField($model,'limit_up', $htmlOptions = array('style'=>'width:40px')); ?></td>
	<td><?php echo $form->labelEx($model,'limit_down'); ?></td>
	<td><?php echo $form->textField($model,'limit_down', $htmlOptions = array('style'=>'width:40px')); ?></td>
</tr>
<tr>
	<td><?php echo $form->labelEx($model,'nextword_delay'); ?></td>
	<td><?php echo $form->textField($model,'nextword_delay', $htmlOptions = array('style'=>'width:40px')); ?></td>

	<td>&nbsp;</td><td>&nbsp;</td>
</tr>


</table>
<?php 
//echo count($words);

//$a = rand(0,count($words)-1 );

//print_r($words[$a]);
?>
<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>
	
<table width="300" border = "0" style="font-size: 18px">
<tr>
	
	<td align="center" valign="middle" width="130" ><div id="rus" >&nbsp;</div></td>
	<td><div id="pod" style="display: none">&nbsp;</div></td>
	<td align="center" valign="middle" width="130"><div id="esp" style="display: none">&nbsp;</div></td>
	
</tr>
<tr>
	
	<td colspan="3"  align="center" valign="middle" ><div id="descr" style="display: none">&nbsp;</div></td>
	
</tr>
</table>




<?php $this->endWidget(); ?>
</div><!-- form -->

<script>

function makeAjaxRequest(){
	$('#pod').css('display', 'none');
	$('#esp').css('display', 'none');
	$('#descr').css('display', 'none');
	jQuery.ajax({
		'type':'POST',
		'url':'<?php echo Yii::app()->createUrl('slovar/espanol')?>',
		'cache':false,
		'async': true,
		'dataType':'json',
		//'data':form.serialize(),
		'data':$('#yw0').serialize(),
		'success':function(response){

			beginShow(response);
			
		},
		'error':function(response, status, xhr){
			makeAjaxRequest();
		}
		});
}

$(document).ready(function(){
	makeAjaxRequest();
});

function unhide(){
	
	
	setTimeout("showPodsk()", 100);
	setTimeout("showEsp()", 600);
	setTimeout("showDescr()", 100);
	setTimeout("makeAjaxRequest()", <?php echo $model->nextword_delay?>);
}


function beginShow(json){
	if(json!=null) {
		//console.log(json);
		$('#rus').html(json.rus);
		$('#esp').html(json.esp);
		$('#pod').html(json.pod);
		$('#descr').html(json.descr);
		//setTimeout("qqq()", 3000);
		setTimeout("unhide()", 1000);
	}
	else{
		//alert('Пустой json');
		 setTimeout("makeAjaxRequest()", 10);

		}
}

function showPodsk(){
	$('#pod').toggle();
}

function showDescr(){
	$('#descr').toggle();
}

function showEsp(){
	$('#esp').toggle(750);
}

</script>


