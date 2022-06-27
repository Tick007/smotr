<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerCoreScript('jquery');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="/themes/<?php echo Yii::app()->theme->name; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/classic/css/main.css?v=<?php echo rand()?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/classic/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Главная', 'url'=>'/'),
			//	array('label'=>'О системе', 'url'=>array('/site/page', 'view'=>'about')),
			//	array('label'=>'Логи/Сообщения', 'url'=>array('/commands/index')),
				array('label'=>'Эмитация приема файла', 'url'=>array('/site/upload')),
				array('label'=>'Полезная нагрузка', 'url'=>array('/payload')),
				array('label'=>'Карта goggle', 'url'=>array('/site/page', 'view'=>'gmap')),
				array('label'=>'Карта openlayers V2', 'url'=>array('/map' )),
			    array('label'=>'ТЕСТ АРМ', 'url'=>array('/site/page', 'view'=>'jwt-alex-vue3')),
	
				//array('label'=>'Юзер рекуэст', 'url'=>array('/site/requestregister')),
				//array('label'=>'Leaderboards' , 'url'=>array('/leaderboards/index')), 
				//array('label'=>'Scores' , 'url'=>array('/scores/index')),	
				//array('label'=>'Achievements' , 'url'=>array('/achievements/index')),	
				//array('label'=>'A. data' , 'url'=>array('/achievementdata/index')),
			//	array('label'=>'Схема v3' , 'url'=>array('/site/scheme3')),
			//	array('label'=>'Циклограммы' , 'url'=>'#', 'itemOptions'=>array('id'=>'ciclo')),
			//	array('label'=>'Контакты', 'url'=>array('/site/contact')),
				
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			))); 
			
		
		?>
	</div><!-- mainmenu -->

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->
<div class="content">
	<?php echo $content; ?>
</div>
	<div id="footer">
		Copyright © <?php echo date('Y'); ?> by GASPROM SPACE SYSTEMS.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?><br>
     </div><!-- footer -->

</div><!-- page -->

</body>
</html>