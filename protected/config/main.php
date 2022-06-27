<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//'name'=>'разработка-сайтов.рф',
	//'name'=>'создание-сайтов.рф',
	'name'=>'СМОТР',
	'charset'=>'utf-8',
	'theme'=>'classic',
	// preloading 'log' component
	'preload'=>array('log', 'db'),
	'language'=>'ru',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1234',
			'ipFilters'=>array('10.10.0.16', '10.10.0.116', '127.0.0.1', '0:0:0:0:0:0:0:1'),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			//'urlSuffix'=>'.gasprom',
			'rules'=>array(
			
			'gii'=>'gii',
			'gii/<controller:\w+>'=>'gii/<controller>',
			'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
			
			    'monitoring/state'=>'site/monitoringstate',
			    
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			'showScriptName'=>false,
		),

	//	'db'=>array(
	//		'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	//	),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
		
			'connectionString' => 'mysql:host=localhost;dbname=smotr_bd',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '1234',
			'charset' => 'utf8',
		    /*
		    'connectionString' => 'pgsql:host=10.10.0.16;port=5432;dbname=smotr_db',
		    'username' => 'postgres',
		    'password' => '1234', // обязателен, пустой может не сработать
		    'charset' => 'utf8',
		    'autoConnect' => false, 
		*/    

		),

	    
		'db2'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'odbc:DRIVER={Microsoft Access Driver (*.mdb)};Dbq=d:\\AZSSKU401\ZSSKU\ZSSKU\LBD_ZS.mdb;',
			'username'=>'Admin',
			'password'=>'qwe',
			'charset' => 'cp1251',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'tick007@yandex.ru',
		'console_start_path'=>'E:\web\wwwroot\sockserver\protected\yiic.bat server --c=start',
	),
);