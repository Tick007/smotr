<?php


//на реге:
//$config=dirname(__FILE__).'/protected/config/paysystem.php';
//$yii=dirname(__FILE__).'/../yii-1.1.21.733ac5/framework/yii.php';

// change the following paths if necessary
//$yii='../../yii-1.1.13.e9e4a0_nodebug/framework/yii.php';
//$yii=dirname(__FILE__).'/../../../web/yii-1.1.21.733ac5/framework/yii.php';//работа  1.1.21
$yii=dirname(__FILE__).'/../../../web/yii-1.1.24.a5ab20/framework/yii.php';
//$yii=dirname(__FILE__).'/../yii-1.1.21.733ac5/framework/yii.php';


//$yii=dirname(__FILE__).'/../../yii-1.1.13.e9e4a0/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';



// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);



require_once($yii);


Yii::createWebApplication($config)->run();

