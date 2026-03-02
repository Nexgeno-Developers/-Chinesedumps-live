<?php
	include("../includes/certManagerDB/db.php");
	$code= $_POST['code'];
	$version= $_POST['version'];
	$os= $_POST['os'];
	$order_number = $_POST['order_number'];
	$serial_number= $_POST['serial_number'];
		
		
	$instanceQuery= "SELECT instance_expiry FROM tbl_package_instance WHERE serial_number = ?";
	
	$stmt = $db->prepare($instanceQuery);
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$stmt->bindParam(1, $serial_number);
	$stmt->execute();
	$num = $stmt->rowCount();		
	
	$instance_expiry = null;
	
	while( $row = $stmt->fetch() ) {  
		$instance_expiry = $row->instance_expiry;
	}
	
	echo $instance_expiry;
	
	$db = null;	
	
?>