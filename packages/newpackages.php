<?php include("../includes/certManagerDB/db.php"); ?>
<?php
	$vendor= $_POST['vendor'];
	$code= $_POST['code'];
	$version= $_POST['version'];
	$swreg_code= $_POST['swreg_code'];
	$name= $_POST['name'];
	$path= $_POST['path'];
	$status= $_POST['status'];
	$package_price= $_POST['package_price'];
	$reg_key= $_POST['reg_key'];
	$type= $_POST['type'];
	$os= $_POST['os'];
	
	/*$vendor= 'Cisco';
	$code= '200-120';
	$version= '12.0';
	//$swreg_code= $_POST['swreg_code'];
	//$name= $_POST['name'];
	$path= 'path';
	//$status= $_POST['status'];
	$package_price= '49.00';
	$reg_key= 'regkey';
	$type= 'Simulator';
	$os= 'Win';*/
		
	try {
	
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
			
		if ($num >= 1)
		{
			if (!empty($reg_key))
			{
				
				$packageKeyQuery = "SELECT package_id FROM tbl_package_engine WHERE package_id = ? AND os = ?";
				$stmt = $db->prepare($packageKeyQuery);
				$stmt->setFetchMode(PDO::FETCH_OBJ);
				$stmt->bindParam(1, $package_id);
				$stmt->bindParam(2, $os);
				$stmt->execute();
				$num = $stmt->rowCount();
				
				$i = 0;	
				if ($num<=0)
				{
									                 
					$engineInsert = "INSERT INTO tbl_package_engine (package_id, version, type, path, os, price)
					                 VALUES(?,?,?,?,?,?)";
	
					$stmt = $db->prepare($engineInsert);
					$stmt->bindParam(1, $package_id);
					$stmt->bindParam(2, $version);
					$stmt->bindValue(3, 'Simulator');
					$stmt->bindParam(4, $path);
					$stmt->bindParam(5, $os);
					$stmt->bindParam(6, $package_price);
					$stmt->execute();
				}				
					
				//get just added engine_id		
	
				$engineId_query = "SELECT id from tbl_package_engine WHERE package_id = ? AND os = ?";
				$stmt = $db->prepare($engineId_query);
				$stmt->setFetchMode(PDO::FETCH_OBJ);
				$stmt->bindParam(1, $package_id);
				$stmt->bindParam(2, $os);
				$stmt->execute();
				$num = $stmt->rowCount();
				while( $row = $stmt->fetch() ) {  
				    $engine_id = $row->id;
				}		
				
				//engine update
				$engineUpdate = "UPDATE tbl_package_engine SET version = ?, price = ?, path = ?  WHERE id = ? 
						 AND os = ?";
	
					$stmt = $db->prepare($engineUpdate);
					$stmt->bindParam(1, $version);					
					$stmt->bindParam(2, $package_price);
					$stmt->bindParam(3, $path);
					$stmt->bindParam(4, $engine_id);
					$stmt->bindParam(5, $os);
					
					$stmt->execute();       			
	
				$packageKeyQuery = "SELECT engine_id FROM tbl_package_regkey WHERE engine_id = ?";
				$stmt = $db->prepare($packageKeyQuery);
				$stmt->setFetchMode(PDO::FETCH_OBJ);
				$stmt->bindParam(1, $engine_id);
				$stmt->execute();
				$num = $stmt->rowCount();
				$i = 0;	
				if ($num >= 1)
				{
					$regKeyUpdate = "UPDATE tbl_package_regkey SET reg_key = ? WHERE engine_id= ?";
					$stmt = $db->prepare($regKeyUpdate);
					$stmt->bindParam(1, $reg_key);
					$stmt->bindParam(2, $engine_id);
					$stmt->execute();
				}
				else
				{
					$regKeyInsert = "INSERT INTO tbl_package_regkey VALUES(?,?)";
					$stmt = $db->prepare($regKeyInsert);
					$stmt->bindParam(1, $engine_id);
					$stmt->bindParam(2, $reg_key);
					$stmt->execute();
				}
			}
		}
		$db = null;
	}
	catch (PDOException $e) {
		echo "Unable to insert/update record";
		die();
	}
?>