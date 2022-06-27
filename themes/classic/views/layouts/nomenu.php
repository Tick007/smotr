<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerCoreScript('jquery');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/classic/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/classic/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/classic/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/classic/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php
$clientScript = Yii::app()->clientScript;
	//$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.paulund_modal_box.js', CClientScript::POS_HEAD);
	//	$clientScript->registerScriptFile('/themes/'.Yii::app()->theme->name.'/js/script.js', CClientScript::POS_HEAD);
?>
</head>

<body>



	
<div class="content">
	<?php echo $content; ?>
</div>




</body>
</html>