<?php include("../includes/certManagerDB/db.php");

	//check if more than two installations already exist	
	$currentDateQuery= "SELECT CURDATE() as date FROM dual";
	
	$stmt = $db->prepare($currentDateQuery);
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$stmt->execute();
	
	$num = $stmt->rowCount();
	while( $row = $stmt->fetch() ) {  
		$date = $row->date;
	}
	echo $date;
	
	$db = null;
?>