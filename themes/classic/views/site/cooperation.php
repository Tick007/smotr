<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxupload.3.5.js', CClientScript::POS_HEAD);
//$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.fineuploader-3.5.0/jquery.fineuploader-3.5.0.js', CClientScript::POS_HEAD);
?>

 <script>
$(document).ready(function() {


	
new AjaxUpload('#article_image', {
  // какому скрипту передавать файлы на загрузку? только на свой домен
  //action: '/adminpages/uploadimg',
  action: '/vacancy/uploadimg',
  autoSubmit: true,               // Отправлять ли файл сразу после выбора
  // имя файла
  name: 'article_image',
  // дополнительные данные для передачи
  data: {
    example_key2 : 'example_value2'
  },
  // авто submit
 // autoSubmit: true,
  // формат в котором данные будет ответ от сервера .
  // HTML (text) и XML определяются автоматически .
  // Удобно при использовании  JSON , в таком случае устанавливаем параметр как "json" .
  // Также установите тип ответа (Content-Type) в text/html, иначе это не будет работать в IE6
  responseType: false,
  // отправка файла сразу после выбора
  // удобно использовать если  autoSubmit отключен  
  onChange: function(file, extension){},
  // что произойдет при  начале отправки  файла 
  onSubmit: function(file, extension) {
	  $('#uploaded').html('<img src="/images/waitanim.gif">');
	  },
  // что выполнить при завершении отправки  файла
  onComplete: function(file, response) {
			$('#uploaded').html(response);
	  }
});



});

</script>

<div class="map_brdcrmbs">
<?php
$brdcrmbs=array(
'О компании'=>array('page/about'),
'Сотрудничество',

);
$this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$brdcrmbs,
			'separator'=>' / ',
			'homeLink'=>false,
			'tagName'=>'p',

	));
?></div>


    <?php if(Yii::app()->user->hasFlash('vacancy')): ?>
<div class="confirmation">
<?php $infomes =Yii::app()->user->getFlash('vacancy'); 
echo $infomes;
?>

</div>
 <?php endif; ?>

<img src="/themes/fortus/img/cooperation.jpg">

<div class="catalog_h1" style="margin-bottom:40px; text-align:justify; margin-top:15px;">
  <h1 style="font-size:18px">СОТРУДНИЧЕСТВО</h1></div>

<span style=" line-height:19px">
Компания «ФОРТУС» - крупнейший российский производитель и поставщик современных механических противоугонных устройств с цилиндрами MUL-T-LOCK, приглашает к сотрудничеству потенциальных партнеров, занимающихся продажей и установкой дополнительного автомобильного оборудования. Мы предлагаем помощь в организации успешного проекта и дальнейшую всестороннюю поддержку в рамках концепции развития бизнеса Компании.</span>
<div class="vacancy_form" style="height:inherit; margin-top: 40px;margin-bottom: 125px; padding-bottom:20px; font-family:Arial, Helvetica, sans-serif ">

    <?php
	if(isset($infomes)) {?><div class="confirmation"><?php
		echo $infomes;
		
	?></div><?php
	}
	 ?>



<?php echo CHtml::beginForm(); ?>
<?php if (isset($_POST['ContactForm']['callback'])==false) echo CHtml::errorSummary($contact); ?>


        <div class="formheader" style="width: 205px;">
        ФОРМА ОБРАТНОЙ СВЯЗИ
        </div>
        <div class="corporativcolor" style="font-size:14px;">Информация о компании</div><br><br>
 <div class="vacformleft">       
 		<div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'city', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'city',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>
		
        <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'people', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'people',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>
        
        <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'company', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'company',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>	 
        
          <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'uadres', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'uadres',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>	 
        
          <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'fadres', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'fadres',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>	 
        
          <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'propertyform', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'propertyform',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>	 
</div>

<div class="vacformright">
		
        <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'familiya', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'familiya',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>
        
        <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'busines', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'busines',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>
        

        <div class="vacblock" style="height:45px; ">
                <div style="float:left">
                <?php echo CHtml::activeLabel($contact,'date', array('required'=>true)); ?>
                <?php echo CHtml::activeTextField($contact,'date',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp', 'style'=>'width:138px')); ?></div>
                 <div style="float:left">
                 <?php echo CHtml::activeLabel($contact,'education', array('style'=>'margin-left:20px;', 'required'=>true)); ?>
                <?php echo CHtml::activeDropDownList($contact,'education',  $contact->educationlist , array('class'=>'vactextinp', 'style'=>'width:170px; margin-left:20px;')); ?>
               </div>
        </div> 
          
        <div class="vacblock" style="margin-bottom:11px;">
			<?php echo CHtml::activeLabel($contact,'body'); ?>
            <?php echo CHtml::activeTextArea($contact,'body',array('cols'=>30,'rows'=>6, 'style'=>'height:38px;')); ?>
        </div>   
        
        <div class="vacblock">
			<?php echo CHtml::activeLabel($contact,'expirience'); ?>
            <?php echo CHtml::activeTextArea($contact,'expirience',array('cols'=>30,'rows'=>6, 'style'=>'height:38px;')); ?>
        </div>   
</div>        
       
<br style="clear:both" />


<div style="width:900px; height:2px; background-color:#FFF; margin-left:-60px; margin-bottom:45px; "></div>
		
    <div>
     <div style="float:left; width:450px">   
        <div class="corporativcolor" style="font-size:14px;">Контактная информация</div><br><br>
        
 		 <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'name', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'name',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>

        
         <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'tel', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'tel',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>
        
         <div class="vacblock">
        <?php echo CHtml::activeLabel($contact,'email', array('required'=>true)); ?>
        <?php echo CHtml::activeTextField($contact,'email',array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp')); ?>
        </div>
	  </div>
      <div style="float:left; width:390px;">
      		  <div class="vacsubblock" style="margin-top: 217px;"><?php
       		echo  CHtml::submitButton(' ', array('class'=>'vacancy_submit'));	
        ?></div> 
      </div>			
	</div>


<br style="clear:both" />


<?php echo CHtml::endForm(); ?>
</div>

