<?php
//print_r($payloadform);

echo CHtml::errorSummary($payloadform); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
       // 'id'=>'miscellaneous-pages-form',
      //  'enableAjaxValidation'=>false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<?php
$downloaded_file = is_file($_SERVER['DOCUMENT_ROOT'].$payloadform->image_uploaded) && file_exists($_SERVER['DOCUMENT_ROOT'].$payloadform->image_uploaded);

?>
<table border="1">
	<tr>
		<td>Файл <?php 
		if(isset($img_size) && is_null($img_size)==false) {?>
		     &nbsp;(размер: <span id="img_size" value="<?php echo $img_size?>"><?php echo $img_size?></span> байт)
		<?php }
		?></td>
		<td>Разбить файл на части</td>
		<td></td>
	</tr>
	<tr>
		<td><?php
		
		
		if($downloaded_file==true){
			?>
			<img alt="" src="<?php echo $payloadform->image_uploaded?>" width="300">
			<?php 
			echo CHtml::activeHiddenField($payloadform,'image_uploaded');
		}
		else echo CHtml::activeFileField($payloadform,'image',array('size'=>10,'maxlength'=>128, 'placeholder'=>'id')); ?></td>
		<td align="center"><?php 
		if($downloaded_file==true){
			
			for($k=1;$k<=$max_chunks; $k++) $chunks[$k]=$k;
			echo CHtml::activedropDownList($payloadform,'chunks_num',$chunks, array('onchange'=>"{Show_chunk_size(this)}"));



		}
			?></td>
		<td></td>
	</tr>
	<tr>
		<td>Станция</td>
		<td><span id="chunk_size"></span></td>
		<td></td>
	</tr>
	<tr>
		<td><?php echo CHtml::activedropDownList($payloadform,'station_id',$station)?></td>
		<td><label>Забить кусок нулями</label>
		<?php echo CHtml::activeCheckbox($payloadform,'addzerros', false)?></td>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>КА</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td><?php echo CHtml::activedropDownList($payloadform,'ka_id',$ka)?></td>
		<td></td>
		<td></td>
	</tr>

</table>

<br>
<div class="action" align="center">
<?php echo CHtml::submitButton('Отправить'); ?>
</div>
<?php $this->endWidget(); ?>

<script>
function Show_chunk_size(el){
	//console.log(el.value);
	let file_size=parseInt(document.getElementById('img_size').innerHTML);
	let chunk_size = file_size/el.value;
	let rounded_size = Math.round(file_size/el.value);
	
	//console.log(chunk_size);
	//console.log(rounded_size);

	if(chunk_size!=rounded_size){
		if(rounded_size>chunk_size){

			let last_chunk = file_size-(rounded_size*(el.value-1));
			//console.log(last_chunk);
			let txt = rounded_size+' x '+(el.value-1)+'<br>'+last_chunk;
			document.getElementById('chunk_size').innerHTML = txt;
		}
		
	}
	else {
		document.getElementById('chunk_size').innerHTML = chunk_size;
	}
	
}
</script>



