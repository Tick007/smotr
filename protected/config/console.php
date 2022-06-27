<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',




		
		'components'=>array(

		
				'db'=>array(
						'connectionString'=>'oci:dbname=10.10.0.230:1521/orcl',
						'username'=>'SYS',
						'password'=>'1234',
				),
		),
		
		'params'=>array(
				// this is used in contact page
				'sockets'=>array(),
				'adminEmail'=>'tick007@yandex.ru',
				'console_start_path'=>'E:\web\wwwroot\sockserver\protected\yiic.bat server --c=start',
		),
);