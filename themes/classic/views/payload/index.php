
<?php
/* @var $this PayloadController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Payloads',
);

$this->menu=array(
	array('label'=>'Create Payload', 'url'=>array('create')),
	array('label'=>'Manage Payload', 'url'=>array('admin')),
);
?>

<h1>Payloads</h1>



<?php




$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'payload-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'station.name',
		'ka.name',
		'received',
		'transfered',
		array(
		'id'=>'ID',
		'type'=>'raw',
		
		'value'=>'CHtml::CheckBox("file", false, array("id"=>"$data->id"))'
		//'value'=>"<input type=checkbox/>"
),
		'file_id',
	),
)); 
		

?>


<div>
<button type="button" id="mybut" value="file">File</button>&nbsp;&nbsp;&nbsp;<button type="button" id="mybut2" value="db">db</button>
</div>

<script>

$(document).ready(function(){

	

	
	$('#mybut').click(function() {
		var ids='?ids=';
		selected = $('input:checked[name^=file]');
		selected.each(function() {
		 //console.log($( this ).attr('id'));
		ids=ids+$( this ).attr('id')+',';
		
		 url='<?php echo Yii::app()->createAbsoluteUrl('site/compose')?>'+ids+'&type=file';
         var windowName = 'Creating file from filesystem';
         window.open(url, windowName, "height=650,width=850");

		});

	});

		$('#mybut2').click(function() {
			var ids='?ids=';
			selected = $('input:checked[name^=file]');
			selected.each(function() {
			 //console.log($( this ).attr('id'));
			ids=ids+$( this ).attr('id')+',';
			
			 url='<?php echo Yii::app()->createAbsoluteUrl('site/compose')?>'+ids+'&type=db';
	         var windowName = 'Creating file from DB';
	         window.open(url, windowName, "height=650,width=850");

			});
		
	});

	

});

</script>