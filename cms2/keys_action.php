<?php



ob_start();

session_start();



	include ("../includes/config/classDbConnection.php");

	include("../includes/common/functions/func_uploadimg.php");

	include ("../includes/common/inc/sessionheader.php");

	include("../includes/common/classes/classUser.php");

	include("../includes/common/classes/classPagingAdmin.php");



//---------------------------------------------------------------

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory

	

include_once 'functions.php';

$ccAdminData = get_session_data();

//----------------------------------------------------------------------------------------------------	

	$show				=	"";

	$title				=	"";

	$resultset			=	"";

	$condition			=	"";

	$limist				=	"";

	$LIMIT				=	"";

	$TotalRecs	     	= 	0;	

	$NaviLinks        	= 	"";	

	$BackNaviLinks	 	= 	"";		

	$ForwardNaviLinks 	= 	"";		

	$TotalPages	      	= 	"";

	$PageNo		      	= 	1;			

	$PageIndex	      	= 	1 ;				

	$rowsPerPage      	= 	100;											

	$linkPerPage      	= 	25;

	$of					=	"of";

//----------------------------------------------------------------------------------------------------		

	

	if(isset($_REQUEST['act']) && $_REQUEST['act']=='reset')

	{

		$instance_id = $_REQUEST['iid'];

		$insert = "update tbl_package_instance set mboard_number='' where id = '$instance_id'";

		$query=mysql_query($insert);

	}

	

	if(isset($_REQUEST['act']) && $_REQUEST['act']=='gen')

	{

		$order_id = $_REQUEST['id'];

		$sql_order = "SELECT *, count(*) as cnt FROM tbl_package_instance where order_number='$order_id'";

//		echo $sql_order;

		$res = mysql_query($sql_order);

		$ordered_package = mysql_fetch_array($res);

		if($ordered_package['cnt'] <= 100)

		{

			$engine_id = $ordered_package['engine_id'];

			

			$time = date("Y-m-d H:i:s");

			$serial_number = md5($order_id.$time);

			$expiry = $ordered_package['instance_expiry'];

			

			$insert="insert into tbl_package_instance (id, engine_id, order_number, serial_number, mboard_number, instance_expiry) Values('', '{$engine_id}', '{$order_id}', '$serial_number', '', '$expiry');";

			$query=mysql_query($insert);

		}

	}



	if(isset($_REQUEST['act']) && $_REQUEST['act']=='updexpiry')

	{

		$instance_id = $_REQUEST['iid'];

		$expiry = $_REQUEST['expiry'];

		$insert = "update tbl_package_instance set instance_expiry='$expiry' where id = '$instance_id'";

		$query=mysql_query($insert);

	}

	header('Location: manage_keys.php?id='.$_REQUEST['id']);

?>