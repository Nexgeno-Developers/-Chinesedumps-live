<?php 

	try {

		$db = new PDO('mysql:host=mysql1008.mochahost.com;dbname=chinesed_umpscom', 'chinesed_dbadmin', '{QOz0I*-5o8I');

		//echo "connected";

		$documentroot = dirname(__FILE__)."/";

	} catch(PDOException $e)

	{

		echo "not able to connect.";

	}



?>