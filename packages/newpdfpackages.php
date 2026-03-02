<?php
include("../includes/certManagerDB/db.php");	

	$vendor= $_POST['vendor'];
	$code= $_POST['code'];
	$version= $_POST['version'];
	$swreg_code= $_POST['swreg_code'];
	$name= $_POST['name'];
	$path= $_POST['path'];
	$status= $_POST['status'];
	$package_price= $_POST['package_price'];
	$type= $_POST['type'];
	$os= $_POST['os'];		
	$questions = $_POST['questions'];
	
        /*$vendor= '3COM';
	$code= '3M0-701';
	$version= '2';
	$swreg_code= $_POST['swreg_code'];
	$name = 'test';
	$path='exam path';
	$status= 't';
	$package_price= '49';
	$type= $_POST['type'];
	$os= $_POST['os'];		
	$questions = '50';*/
	
	try {
		//get vendor id
		$vendorQuery = "SELECT ven_id FROM tbl_vendors WHERE ven_name = ?";
		$stmt = $db->prepare($vendorQuery);
		$stmt->setFetchMode(PDO::FETCH_OBJ);
		$stmt->bindParam(1, $vendor);
		$stmt->execute();
		while( $row = $stmt->fetch() ) {  
		    $vendor_id = $row->ven_id;
		}
		
		
		//check if package already exist	
		$packageQuery= "SELECT exam_id as package_id FROM tbl_exam WHERE exam_name = ?";
		
		$stmt = $db->prepare($packageQuery);
		$stmt->setFetchMode(PDO::FETCH_OBJ);
		$stmt->bindParam(1, $code);
		$stmt->execute();
		$num = $stmt->rowCount();
		while( $row = $stmt->fetch() ) {  
		    $package_id = $row->package_id;
		}	
	
		if ($num >= 1) {
			
			$updatePackageQuery = "UPDATE tbl_exam SET exam_upddate= ?, exam_fullname = ?,
			                       QA= ?, version = ?, exam_full = ?, ven_id = ?
			                       WHERE exam_name = ? AND exam_id = ?";
			
			$stmt = $db->prepare($updatePackageQuery);
			$stmt->bindParam(1, date("Y-m-d"));
			$stmt->bindParam(2, $name);
			$stmt->bindParam(3, $questions);
			$stmt->bindParam(4, $version);
			$stmt->bindParam(5, $path);
			$stmt->bindParam(6, $vendor_id);
			$stmt->bindParam(7, $code);
			$stmt->bindParam(8, $package_id);
			
			$stmt->execute();
		}
		else {
						
			$insertPackageQuery = "INSERT INTO tbl_exam(ven_id, cert_id , exam_name, version, exam_fullname,
						 QA, exam_status, exam_date, exam_full, exam_pri0) 
						 VALUES (?,?,?,?,?,?,?,?,?,?)";	

			
			$stmt = $db->prepare($insertPackageQuery);
			$stmt->bindParam(1, $vendor_id);
			$stmt->bindValue(2, 'n');
			$stmt->bindParam(3, $code);
			$stmt->bindParam(4, $version);
			$stmt->bindParam(5, $name);
			$stmt->bindParam(6, $questions);
			$stmt->bindValue(7, '0');
			$stmt->bindParam(8, date("Y-m-d"));
			$stmt->bindParam(9, $path);
			$stmt->bindParam(10, $package_price);
			$stmt->execute();
		}
		$db = null;
	}
	catch (PDOException $e) {
		echo "Unable to insert/update record";
		die();
	}
?>