<?php

ob_start();

session_start();



	include ("../includes/config/classDbConnection.php");

	include("../includes/common/functions/func_uploadimg.php");

	include ("../includes/common/inc/sessionheader.php");

	include("../includes/common/classes/classUser.php");

	include("../includes/common/classes/classPagingAdmin.php");

	include("../functions.php");


//---------------------------------------------------------------

	$objDBcon    = new classDbConnection; 			// VALIDATION CLASS OBJECT

	$objUser	 = new classUser($objDBcon);	// VALIDATION CLASS classCategory

	


include_once 'functions.php';

$ccAdminData = get_session_data();

//-----------------------------------------------------------------

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

	if(isset($_POST['Search'])){

	$rowsPerPage      	= 	1000000000;

	}else{

	$rowsPerPage      	= 	100;

	}														

	$linkPerPage      	= 	25;

	$of					=	"of";

//----------------------------------------------------------------------------------------------------		



		if(!isset($pgObj) && empty($pgObj))

				$pgObj 		=  new classPaging ("manageuser.php",$rowsPerPage,$linkPerPage,"","","");

		

		if(isset($_GET['pgIndex']) && !empty($_GET['pgIndex']) && is_numeric($_GET['pgIndex']) && is_numeric($_GET['currentPage']))

				{

					$PageNo			 = $_GET['currentPage'];	# Shows # of Page			

					$PageIndex		 = $_GET['pgIndex'];		# Shows # of Page Index				

				}	



					$LIMIT .= " LIMIT " . (($PageNo-1) * $rowsPerPage). " , " . $rowsPerPage;

					

	// Variable for setting Stats type

	$ref = $_GET['ref'];

//----------------------------------------------------------------------------------------------------	

    if(isset($_GET['action']) && $_GET['action']	== "up" )

	{

	$objUser->deleteUser($_GET['nid']);

	}



	if(isset($_POST['btn_delete']) &&	$_POST['btn_delete']	== "Delete Selected Items" )

	{		

			$counter	=	$_POST['counter']; 

			for($i=1; $i<=$counter; $i++)

				{ 

					

					if(isset($_POST['chkbox'.$i])){

					$objUser->deleteUser($_POST['chkbox'.$i]);

					}

				}

	}

	

	if(isset($_GET['mainsend']) && $_GET['mainsend']=='send'){

		   $getuser	=	mysql_fetch_array(mysql_query("select * from tbl_user where user_id='".$_GET['user']."'"));

		   $spram[1]	= $getuser['user_fname'];

		   $spram[2]	= $getuser['user_lname'];

		   $spram[5]	= $getuser['user_email'];

		   		

		signupemail($spram,$from);

		$str22	=	"Email has been successfully sent.";

		

	}

	

	if(isset($_POST['Search']))

	{

		if($_POST['textSearch']!=''){

			if($_POST['txt_StartDate']=='' && $_POST['txt_StartDate2']==''){

				$cri	=	$_POST['criteria']." LIKE '%".$_POST['textSearch']."%' ";

			}else{

				$cri	=	$_POST['criteria']." LIKE '%".$_POST['textSearch']."%' and ";

				}

			}else{

			$cri	=	'';

			}

		if($_POST['txt_StartDate']!=''){

		if($_POST['txt_StartDate2']!=''){

		$stDate	=	" creatDate>='".$_POST['txt_StartDate']."' and ";

		}else{

		$stDate	=	"creatDate>='".$_POST['txt_StartDate']."'";

		}}else{

		$stDate	=	'';

		}

		

		if($_POST['txt_StartDate2']!='')

		{

			$enDate	=	"creatDate<='".$_POST['txt_StartDate2']."'";

		}

		else

		{

			$enDate	=	'';

		}

		if(isset($_POST['criteria']) && $_POST['criteria']=='Order_Id')

		{

			$que	=	"SELECT * FROM tbl_user INNER JOIN order_master ON (order_master.Cust_ID=tbl_user.user_id) WHERE order_master.Order_Id LIKE '%".$_POST['textSearch']."%'";

			$result1	=	mysql_query($que);

			$pgObj->SetNavigationalLinksNew($result1);

			$result= mysql_query($que);

		}

		else

		{

			$result1	=	mysql_query("select * from tbl_user where ".$cri." ".$stDate." ".$enDate." order by creatDate DESC".$limist);

			$pgObj->SetNavigationalLinksNew($result1);

			$result= mysql_query("select * from tbl_user where ".$cri." ".$stDate." ".$enDate." order by creatDate DESC".$LIMIT);

		}

	

	}

	else

	{

		if($ref=="all")

		{

			$qry = "select * from tbl_user order by `user_id` DESC".$limist;

			$result1 = mysql_query($qry);

			$pgObj->SetNavigationalLinksNew($result1);

			$qry2 = "select * from  tbl_user order by `user_id` DESC".$LIMIT;

			$result = mysql_query($qry2);

		}

		else if($ref=="p")

		{

			$qry = "SELECT * FROM tbl_user where user_id in (Select Cust_Id from order_master where Status='1') order by `user_id` DESC";

			$result1 = mysql_query($qry);

			$pgObj->SetNavigationalLinksNew($result1);

			$qry2 = "SELECT * FROM tbl_user where user_id in (Select Cust_Id from order_master where Status='1') order by `user_id` DESC".$LIMIT;

			$result = mysql_query($qry2);

		}

		else if($ref=="np")

		{

			$qry = "SELECT * FROM tbl_user where user_id not in (Select Cust_Id from order_master where Status='1') order by `user_id` DESC";

			$result1 = mysql_query($qry);

			$pgObj->SetNavigationalLinksNew($result1);

			$qry2 = "SELECT * FROM tbl_user where user_id not in (Select Cust_Id from order_master where Status='1') order by `user_id` DESC".$LIMIT;

			$result = mysql_query($qry2);

		}

	}

//............................................

//............................................



	if(mysql_num_rows($result) < 1)

	{

		$emptyError	=	"No record exist.";

	}

	$show		 =	$objUser->getUserhtml($result, 1);



//------------------------html---------------------------------------



include("html/userstats.html");



//------------------------html---------------------------------------



?>