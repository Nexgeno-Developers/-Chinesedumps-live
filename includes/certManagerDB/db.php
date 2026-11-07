<?php 

	try {

		$db = new PDO('mysql:host=mysql1008.mochahost.com;dbname=chinesed_umpscom', 'chinesed_dbadmin', '{QOz0I*-5o8I');

		//echo "connected";

		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); // keep PHP 5 behaviour (PHP 8 defaults to exceptions)
		$documentroot = dirname(__FILE__)."/";

	} catch(PDOException $e)

	{

		echo "not able to connect.";

	}



?>