<?php 
/////cheeta



function permission($section, $permission, $halt = false) {
	global $ccAdminData;
	// check if index exists and if not create it
	if (!isset($ccAdminData[$section][$permission])) {
		$ccAdminData[$section][$permission] = '';
	}	
	
	$result = ($ccAdminData[$section][$permission] == true ) ? true : false;
	
	if (!$result && $halt == true) httpredir($GLOBALS['rootRel'].$glob['adminFile']."?_g=401");
	
	return $result;
}
## Page redirection
function httpredir($target) {
#	$target = preg_replace('#/+#', '/', urldecode($target));
	header('Location: '.html_entity_decode(str_replace('amp;', '', $target)));
	exit;
}

////////////////////////

function get_session_data() {

		$objDBcon = new classDbConnection(); 
	//	global $objDBcon;
		//$objDBcon    = new classDbConnection;
		if (!isset($_SESSION['strAdmin'])) {
			## If no session redirect to login screen
			header("location:index.php");
		} else { 
			## Get session information as array
			$query = "SELECT * FROM tbladmin WHERE admin_id='".$_SESSION['adminId']."'";
			$exeque	=	mysql_query($query);
			$retArr="";
			$i=0;
			while($row	= mysql_fetch_array($exeque)){
			$retArr[$i++]=$row;
			}
			$ccAdminData	=	$retArr;
			//$ccAdminData = $objDBcon->getTableFromDB($query);
			//if (!$ccAdminData[0]['isSuper']) {
			$ses = $_SESSION['adminId'];
	    	
			$query2 =mysql_query("SELECT tblmodules.ModuleID, Name, `Read`, `Write`, `Edit`, `Delete` FROM tblmodules LEFT JOIN tblpermissions ON tblmodules.ModuleID = tblpermissions.ModuleID WHERE AdminID ='".$ses."'");
			$retArr2="";
			$y=0;
			while($row2	= mysql_fetch_array($query2)){
			$retArr2[$y++]=$row2;
			}
			$permissionArray	=	$retArr2;
				// $permissionArray = $objDBcon->getTableFromDB($query);
				
				#die;
				
				if (is_array($permissionArray)) {
					for ($i=0; $i<count($permissionArray); $i++) {
						foreach ($permissionArray[$i] as $key => $value) {
							$masterKey = $permissionArray[$i]['Name'];
							$ccAdminData[0][$masterKey][$key] = $value;
						}
					}
				}
			}
			return $ccAdminData[0];
		}

///cheeta
?>