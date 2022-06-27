<?php

class  WebsocketController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	 
	 
	 

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	
	public function actionStart(){
			
			set_time_limit(5);
			ini_set('max_execution_time', 180);
		
		
			$dstip =  Yii::app()->getRequest()->getParam('dstip', NULL);
			$ctxip =  Yii::app()->getRequest()->getParam('ctxip', NULL);
			$dstport =  Yii::app()->getRequest()->getParam('dstport', NULL);
			$dstab =  Yii::app()->getRequest()->getParam('dstab', NULL);
			$srcab =  Yii::app()->getRequest()->getParam('srcab', NULL);
			$lvsnumsrc =  Yii::app()->getRequest()->getParam('lvsnumsrc', NULL);
			$lvsnumdst =  Yii::app()->getRequest()->getParam('lvsnumdst', NULL);
			
			
			//E:\web\wwwroot\arm\protected\yiic websocket index --command=websocketstart --site=arm  --lvsnumsrc=20 --lvsnumdst=21 --dstab=3;
			
			$yiicpath = 'D:\web\wwwroot\smotr\protected\yiic.bat ws --c=start';
			echo $yiicpath;
			//exec( $yiicpath, $output, );
			shell_exec($yiicpath);
			//system($yiicpath);
			//echo $output;
			exit();
			
	}
	
	
	
}















