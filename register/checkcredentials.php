<?php
	include("../includes/certManagerDB/db.php");

    $debug = false;

	$code = $_POST['code'];;

	$order_number = $_POST['order_number'];

	$serial_number = $_POST['serial_number'];

	$mboard_number = $_POST['mboard_number'];

	$os= $_POST['os'];

	

	// Get Engine Id	

	$engineQuery = "SELECT distinct id FROM tbl_package_engine WHERE os = ? AND 

			package_id = (SELECT distinct exam_id FROM tbl_exam WHERE exam_name = ?)";



	if ($debug) {

		echo $engineQuery.'<br/>';

	}

	$stmt = $db->prepare($engineQuery);

	$stmt->setFetchMode(PDO::FETCH_OBJ);

	$stmt->bindParam(1, $os);

	$stmt->bindParam(2, $code);

	$stmt->execute();

	$num = $stmt->rowCount();		

	

	while( $row = $stmt->fetch() ) {  

		$engine_id = $row->id;

	}	

	

	if ($debug) {

		echo 'engine_id: '.$engine_id.'<br/>';

	}



	// Get Instance Expiry	

	$expiryQuery = "SELECT instance_expiry FROM tbl_package_instance 

			WHERE engine_id = ? AND order_number = ? AND serial_number = ?";

			

	$stmt = $db->prepare($expiryQuery);

	$stmt->setFetchMode(PDO::FETCH_OBJ);

	$stmt->bindParam(1, $engine_id);

	$stmt->bindParam(2, $order_number);

	$stmt->bindParam(3, $serial_number);

	$stmt->execute();

	$num = $stmt->rowCount();

	

	while( $row = $stmt->fetch() ) {  

		$instance_expiry = $row->instance_expiry;

	}	

	

	if ($debug) {

		echo 'instance_expiry: '.$instance_expiry.'<br/>';

	}

	

	//Reinstallation

	$reinstallationQuery = "SELECT id FROM tbl_package_instance 

				WHERE order_number = ? AND serial_number = ? 

				AND mboard_number = ? AND instance_expiry >= now() AND engine_id = ?";

	

	$stmt = $db->prepare($reinstallationQuery);

	$stmt->setFetchMode(PDO::FETCH_OBJ);

	$stmt->bindParam(1, $order_number);

	$stmt->bindParam(2, $serial_number);

	$stmt->bindParam(3, $mboard_number);

	$stmt->bindParam(4, $engine_id);

	

	$stmt->execute();

	$num = $stmt->rowCount();

	

	if ($debug) {

		echo 'num: '.$num.'<br/>';

	}

	

	

	while( $row = $stmt->fetch() ) {  

		$instance_id = $row->id;

	}	

	

	if ($debug) {

		echo 'instance_id: '.$instance_id.'<br/>';

	}

	

	if ($num == 1) {

		$regKeyQuery = "SELECT reg_key FROM tbl_package_regkey WHERE engine_id = ?";

		

		if ($debug) {

			echo 'regKeyQuery: '.$regKeyQuery.'<br/>';

		}

		

		$stmt = $db->prepare($regKeyQuery);

		$stmt->setFetchMode(PDO::FETCH_OBJ);

		$stmt->bindParam(1, $engine_id);

	

		$stmt->execute();

		$num = $stmt->rowCount();

		

		if ($debug) {

			echo 'regKeyQuery-> num: '.$num.'<br/>';

		}

		

		while( $row = $stmt->fetch() ) {  

			$reg_key = $row->reg_key;

			echo $reg_key.'@'.$instance_expiry;

		}		

		

	}

	else {

		//Check if More than installation for one Order + Serial	

		$oneInstallationQuery = "SELECT id FROM tbl_package_instance 

				 	 WHERE order_number = ? AND serial_number = ? 

					 AND (mboard_number is NOT NULL AND mboard_number != '')

					 AND mboard_number != ?

				 	 AND engine_id = ?";

		

		$stmt = $db->prepare($oneInstallationQuery);

		$stmt->setFetchMode(PDO::FETCH_OBJ);

		$stmt->bindParam(1, $order_number);

		$stmt->bindParam(2, $serial_number);

		$stmt->bindParam(3, $mboard_number);

		$stmt->bindParam(4, $engine_id);

		

		$stmt->execute();

		$num = $stmt->rowCount();

		

		if ($num == 1){

			echo "3"; //EXCESS 

		}

		else {

			$firstInstallationQuery = "SELECT id FROM tbl_package_instance 

					 	   WHERE order_number = ? AND serial_number = ? 

						   AND (mboard_number IS NULL OR mboard_number = '')

					 	   AND engine_id = ?";

			

			$stmt = $db->prepare($firstInstallationQuery);

			$stmt->setFetchMode(PDO::FETCH_OBJ);

			$stmt->bindParam(1, $order_number);

			$stmt->bindParam(2, $serial_number);

			$stmt->bindParam(3, $engine_id);

			

			$stmt->execute();

			$num = $stmt->rowCount();			

			

			if ($num == 1){

				$mboard_update_query = "UPDATE tbl_package_instance 

				                        SET mboard_number = ?, activation_date = now()

					  		WHERE order_number = ? AND serial_number = ? 

	 			  			AND engine_id = ?";

	 			  			

		 		$stmt = $db->prepare($mboard_update_query);

				$stmt->setFetchMode(PDO::FETCH_OBJ);

				$stmt->bindParam(1, $mboard_number);

				$stmt->bindParam(2, $order_number);

				$stmt->bindParam(3, $serial_number);

				$stmt->bindParam(4, $engine_id);

				

				$stmt->execute();

			

				

				$regKeyQuery = "SELECT reg_key FROM tbl_package_regkey WHERE engine_id = ?";

				

				$stmt = $db->prepare($regKeyQuery);

				$stmt->setFetchMode(PDO::FETCH_OBJ);

				$stmt->bindParam(1, $engine_id);

				

				$stmt->execute();

		

				while( $row = $stmt->fetch() ) {  

					$reg_key = $row->reg_key;

					echo $reg_key.'@'.$instance_expiry;

				}

				

			}

			else {

				echo '-1'; //NO RECORD OR EXPIRED

			}

		}

					

	}

	

	$db = null;	

	

?>