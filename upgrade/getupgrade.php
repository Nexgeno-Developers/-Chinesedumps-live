<?php
	include("../includes/certManagerDB/db.php");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");

	
	$code= $_POST['code'];
	$version= $_POST['version'];
	$os= $_POST['os'];
	$order_number = $_POST['order_number'];
	$serial_number= $_POST['serial_number'];

		
	$packageQuery= "SELECT package_id, version FROM tbl_package_engine 
	WHERE package_id = (Select id from exam WHERE code = ?)";
	
	$stmt = $db->prepare($packageQuery);
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$stmt->bindParam(1, $code);
	$stmt->execute();
	$num = $stmt->rowCount();		
	
	while( $row = $stmt->fetch() ) {  
		$package_id = $row->package_id;
		$package_version = $row->version;
	}
			
	floatval($package_version);
	floatval($version);
	

	
	if (floatval($package_version) <> floatval($version)) {
		$instanceQuery = "SELECT id FROM tbl_package_instance
				  WHERE serial_number = ? AND instance_expiry >= now()";
		
		$stmt = $db->prepare($instanceQuery);
		$stmt->setFetchMode(PDO::FETCH_OBJ);
		$stmt->bindParam(1, $serial_number);
		
		$stmt->execute();
		$num = $stmt->rowCount();
		
		if ($num > 0){
			$updatePackageQuery = "SELECT path FROM tbl_package_engine
			                       WHERE package_id = ? AND os = ?";
			                       
			$stmt = $db->prepare($updatePackageQuery);
			$stmt->setFetchMode(PDO::FETCH_OBJ);
			$stmt->bindParam(1, $package_id);
			$stmt->bindParam(2, $os);
			
			$stmt->execute();
			$num = $stmt->rowCount();
										                       	 
			while( $row = $stmt->fetch() ) {  
				$package_path= $row->path;
			}
			
			if (isset($package_path)) {
				$file_path = $documentroot.'/pdenfiles/full/'.str_ireplace('Sim','Update',$package_path);
				$path_parts = pathinfo($file_path);
				$file_name  = $path_parts['basename'];
				if (file_exists($file_path))
				{
					header("Content-Disposition: attachment; filename=\"$file_name\"");
					readfile($file_path);					
				}
				else {
					echo "false";
				}
			}
			else {
				echo "false";
			}					
			                      
		}
		else {
			echo "false";	
		}
	}
	else {
		echo "false";
	}
	
	$db = null;	
	
?>